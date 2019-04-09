<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class MaterialController extends BaseController {

	public function openFilterPage() {
		return View::make('materials.filter', array(
			'page' => 'materials'
		));
	}

	public function openFilterListPage() {
		$data = Input::all();
		$materials = self::getFilterMaterials($data);
		$fullURL = Request::fullUrl();
		$query = explode("?", $fullURL);
		return View::make('materials.filter-list', array(
			'page' => 'materials',
			'materials' => $materials,
			'parameter' => $query[1]
		));
	}

	function getFilterMaterials($data) {
		$materialsTable = DB::table('materials');
		if (isset($data['material_numbers']) && strlen($data['material_numbers']) > 0) {
			$materials = explode(",", $data['material_numbers']);
			$materialsTable = $materialsTable->whereIn('material_number', $materials);
		}
		if (isset($data['locations']) && strlen($data['locations']) > 0) {
			$locations = explode(",", $data['locations']);
			$materialsTable = $materialsTable->whereIn('materials.location', $locations);
		}
		if (isset($data['description']) && strlen($data['description']) > 0) {
			$materialsTable = $materialsTable->where('materials.description', 'LIKE', '%' . $data['description'] . '%');
		}
		if (isset($data['lead_time_from']) && strlen($data['lead_time_from']) > 0 && isset($data['lead_time_until']) && strlen($data['lead_time_until']) > 0) {
			$from = intval($data['lead_time_from']);
			$until = intval($data['lead_time_until']);
			if ($from != $until) {
				$materialsTable = $materialsTable->whereBetween('materials.lead_time', array($from, $until));
			}
			else {
				$materialsTable = $materialsTable->where('materials.lead_time', '=', $from);	
			}
		}
		$materials = $materialsTable->orderBy('created_at', 'asc')->get();
		return $materials;
	}

	/**
	 * Display a list materials page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		// $materials = Material::all();
		$materials = Material::count();
		return View::make('materials.list', array(
			'page' => 'materials',
			'materials' => $materials
		));
	}

	/**
	 * Display a add material page
	 *
	 * @return View
	 */

	public function openAddPage() {
		return View::make('materials.add', array(
			'page' => 'materials'
		));
	}

	/**
	 * Save material
	 *
	 * @return
	 */

	public function createMaterial() {

		// Completion
		$data = Input::all();
		$validator = Validator::make($data, Material::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data["user_id"] = Session::get('id');
		$material = Material::create($data);

		return Redirect::route('materials');
	}

	/**
	 * Display a detail material page
	 * 
	 * @param   $id identity of material
	 *
	 * @return View
	 */

	public function openDetailPage($id) {
		try {
			$material = Material::findOrFail($id);
			return View::make('materials.detail', array(
				'page' => 'materials',
				'material' => $material
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit material page
	 *
	 * @return View
	 */

	public function openEditPage($id) {
		try {
			$material = Material::findOrFail($id);
			return View::make('materials.edit', array(
				'page' => 'materials',
				'material' => $material
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Update category
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function updateMaterial($id) {

		try {
			// Product
			$material = Material::findOrFail($id);
			$data = Input::all();
			$validator = Validator::make($data, Material::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$data["user_id"] = Session::get('id');
			$material->update($data);
			return Redirect::route('materials');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete material
	 * 
	 * @param   $id identity of material
	 *
	 * @return
	 */

	public function deleteMaterial($id) {
		// echo Request::url();
		// exit();

		try {
			// Product
			$material = Material::findOrFail($id);
			$material->delete();
			return Redirect::route('materials');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	public function deleteAllFilteredMaterial() {
		$fullURL = Request::fullUrl();
		$url = Request::url();
		$queryString = str_replace(Request::url(), "", $fullURL);
		$url = Request::root() . "/materials/filter/list" . $queryString;
		// echo Request::path();
		$data = Input::all();
		$materials = self::getFilterMaterials($data);
		foreach ($materials as $material) {
			$inventory = self::getInventoryLot($material->material_number);
			if (isset($inventory)) {
				if ($inventory->lot == 0) {
					DB::table('transfers')->where('material_id', '=', $material->$id)->delete();
					DB::table('completions')->where('material_id', '=', $material->$id)->delete();
					DB::table('materials')->where('id', '=', $material->id)->delete();
				}
			}
			else {
				DB::table('transfers')->where('material_id', '=', $material->$id)->delete();
				DB::table('completions')->where('material_id', '=', $material->$id)->delete();
				DB::table('materials')->where('id', '=', $material->id)->delete();
			}
		}
		return Redirect::to($url);
	}

	/**
	 * Export completions to Excel File
	 *
	 * @return Ms. Excel File
	 *
	 */

	public function exportToExcel() {

		$data = Input::all();
		$materials;
		if (isset($data)) {
			$materials = self::getFilterMaterials($data);
		}
		else {
			$materials = Material::all();
		}

		$data = array(
			'materials' => $materials
		);
        Excel::create('Master Materials', function($excel) use ($data){
		    $excel->sheet('Master', function($sheet) use ($data) {
		        return $sheet->loadView('materials.excel', $data);
		    });
		})->export('xls');
	}

	/**
	 * Import material master from Excel File
	 *
	 * @return List Transfer
	 *
	 */

	public function importFromExcel() {

		$data = Input::all();

		if (Input::hasFile('excel')) {
		    $file = $data['excel'];
		    $file->move('excels', $file->getClientOriginalName());
			$excel = "excels/" . $file->getClientOriginalName();
			Excel::load($excel, function($reader) {
				$reader->each(function($row) {
					$data = Array();
					$material = Material::where("material_number", "=", $row->material_number)->first();
					if ($material == null) {
						$data['material_number'] = $row->material_number;
						$data['description'] = $row->description;
						$data['location'] = $row->location;
						$data['lead_time'] = $row->lead_time;
						$data["user_id"] = Session::get('id');
						if ($data['material_number'] != null) {
							$material = Material::create($data);
						}
					}
				});
			});
			return Redirect::route('materials');
		}
	}

	public function checkMaterial($material_number) {

		try {
			$material = Material::where('material_number', '=', $material_number)
						->firstOrFail();
			echo json_encode($material);
		}
		catch(ModelNotFoundException $e) {
			echo '';
		}
	}


}