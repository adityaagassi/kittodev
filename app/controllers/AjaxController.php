<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class AjaxController extends BaseController {

	/**
	 * Display a list materials json
	 *
	 * @return View
	 */
	
	public function materialJSON() {
		$iDisplayStart = $_GET['iDisplayStart'];
		$iDisplayLength = $_GET['iDisplayLength'];
		$iTotalRecords = DB::table('materials')->whereNull('deleted_at')->count();
		$aaData = DB::table('materials')->whereNull('deleted_at')->skip($iDisplayStart)->take($iDisplayLength)->orderBy('material_number', 'asc')->get();
		$results = array(
			"iTotalRecords" => $iTotalRecords,
			"iTotalDisplayRecords" => $iTotalRecords,
			"aaData" => $aaData
		);
		return Response::json($results);
	}

	/**
	 * Display a list materials json
	 *
	 * @return View
	 */
	
	public function deleteMaterialJSON($id) {
		try {
			$material = Material::findOrFail($id);
			if (isset($material)) {
				$inventory = self::getInventoryLot($material->material_number);
				if (isset($inventory)) {
					if ($inventory->lot == 0) {
						$material->delete();
						$completions = DB::table('completions')->where('material_id', '=', $id)->delete();
						$transfers = DB::table('transfers')->where('material_id', '=', $id)->delete();
						$results = array(
							"status" => true,
							"message" => "Material berhasil dihapus"
						);
						return Response::json($results);
					}
					else {
						$results = array(
							"status" => false,
							"message" => "Material masih tersisa di inventory"
						);
						return Response::json($results);
					}
				}
				else {
					$completions = DB::table('completions')->where('material_id', '=', $id)->delete();
					$transfers = DB::table('transfers')->where('material_id', '=', $id)->delete();
					$results = array(
						"status" => true,
						"message" => "Material berhasil dihapus"
					);
					return Response::json($results);
				}
			}
			else {
				$results = array(
					"status" => false,
					"message" => "Material tidak terdaftar"
				);
				return Response::json($results);
			}
		}
		catch(ModelNotFoundException $e) {
			$results = array(
				"success" => false,
				"message" => "Hapus material gagal"
			);
			return Response::json($results);
		}
		// $iTotalRecords = DB::table('materials')->whereNull('deleted_at')->count();
		// $aaData = DB::table('materials')->whereNull('deleted_at')->skip($iDisplayStart)->take($iDisplayLength)->orderBy('material_number', 'asc')->get();
		// $results = array(
		// 	"iTotalRecords" => $iTotalRecords,
		// 	"iTotalDisplayRecords" => $iTotalRecords,
		// 	"aaData" => $aaData
		// );
		// return Response::json($results);
	}

	function deleteMaterial($id) {
		$material = Material::findOrFail($id);
		if (isset($material)) {
			$inventory = self::getInventoryLot($material->material_number);
			if (isset($inventory)) {
				if ($inventory->lot == 0) {
					$completions = DB::table('completions')->where('material_id', '=', $id)->delete();
					$transfers = DB::table('transfers')->where('material_id', '=', $id)->delete();
					$results = array(
						"success" => true,
						"message" => "Material berhasil dihapus"
					);
					return Response::json($results);
				}
				else {
					$results = array(
						"success" => false,
						"message" => "Hapus material gagal, material masih tersisa di inventory"
					);
					return Response::json($results);
				}
			}
			else {
				$completions = DB::table('completions')->where('material_id', '=', $id)->delete();
				$transfers = DB::table('transfers')->where('material_id', '=', $id)->delete();
				$results = array(
					"success" => true,
					"message" => "Material berhasil dihapus"
				);
				return Response::json($results);
			}
		}
		else {
			$results = array(
				"success" => false,
				"message" => "Hapus material gagal, material tidak terdaftar"
			);
			return Response::json($results);
		}
	}

	/**
	 * Display a list completions json
	 *
	 * @return View
	 */
	
	public function completionJSON() {
		$iDisplayStart = $_GET['iDisplayStart'];
		$iDisplayLength = $_GET['iDisplayLength'];
		$iTotalRecords = DB::table('completions')->whereNull('completions.deleted_at')->count();
		$aaData = DB::table('completions')
					->join('materials', 'completions.material_id', '=', 'materials.id')
					->join('users', 'completions.user_id', '=', 'users.id')
					->whereNull('completions.deleted_at')
					->select(
						'completions.id',
						'completions.barcode_number', 
						'completions.issue_plant', 
						'completions.lot_completion', 
						'completions.material_id', 
						'materials.material_number', 
						'materials.description',
						'materials.location', 
						'completions.limit_used',
						'completions.user_id', 
						'users.name', 
						'completions.active', 
						'completions.deleted_at', 
						'completions.created_at', 
						'completions.updated_at'
					)
					->skip($iDisplayStart)
					->take($iDisplayLength)
					->orderBy('barcode_number', 'asc')
					->get();
		$results = array(
			"iTotalRecords" => $iTotalRecords,
			"iTotalDisplayRecords" => $iTotalRecords,
			"aaData" => $aaData
		);
		return Response::json($results);
	}

	/**
	 * Display a list completions json
	 *
	 * @return View
	 */
	
	public function deleteCompletionJSON($id) {
		try {
			$completion = Completion::findOrFail($id);
			if (isset($completion)) {
				$completion->delete();
				$transfers = DB::table('transfers')->where('completion_id', '=', $id)->delete();
				$results = array(
					"status" => true,
					"message" => "Completion berhasil dihapus"
				);
				return Response::json($results);
			}
			else {
				$results = array(
					"status" => false,
					"message" => "Completion tidak terdaftar"
				);
				return Response::json($results);
			}
		}
		catch(ModelNotFoundException $e) {
			$results = array(
				"success" => false,
				"message" => "Hapus completion gagal"
			);
			return Response::json($results);
		}
	}

	/**
	 * Display a list transfers json
	 *
	 * @return View
	 */
	
	public function transferJSON() {
		$iDisplayStart = $_GET['iDisplayStart'];
		$iDisplayLength = $_GET['iDisplayLength'];
		$iTotalRecords = DB::table('transfers')->whereNull('transfers.deleted_at')->count();
		$aaData = DB::table('transfers')
					->join('completions', 'transfers.completion_id', '=', 'completions.id')
					->join('materials', 'completions.material_id', '=', 'materials.id')
					->join('users', 'transfers.user_id', '=', 'users.id')
					->whereNull('transfers.deleted_at')
					->select(
						'transfers.id',
						'transfers.barcode_number_transfer', 
						'completions.material_id', 
						'materials.material_number', 
						'materials.description', 
						'transfers.lot_transfer', 
						'completions.barcode_number', 
						'transfers.issue_location', 
						'transfers.receive_location', 
						'completions.user_id', 
						'users.name', 
						'transfers.deleted_at', 
						'transfers.updated_at',
						'transfers.created_at'
					)
					->skip($iDisplayStart)
					->take($iDisplayLength)
					->orderBy('transfers.barcode_number_transfer', 'asc')
					->get();
		$results = array(
			"iTotalRecords" => $iTotalRecords,
			"iTotalDisplayRecords" => $iTotalRecords,
			"aaData" => $aaData
		);
		return Response::json($results);
	}

	/**
	 * Display a list materials json
	 *
	 * @return View
	 */
	
	public function deleteTransferJSON($id) {
		try {
			$transfer = Transfer::findOrFail($id);
			if (isset($transfer)) {
				$material = Material::findOrFail($transfer->material_id);
				if (isset($material)) {
					$inventory = self::getInventoryLot($material->material_number);
					if (isset($inventory)) {
						if ($inventory->lot == 0) {
							$transfer->delete();
							$results = array(
								"status" => true,
								"message" => "Transfer berhasil dihapus"
							);
							return Response::json($results);
						}
						else {
							$results = array(
								"status" => false,
								"message" => "Transfer masih tersisa di inventory"
							);
							return Response::json($results);
						}
					}
					else {
						$completions = DB::table('completions')->where('material_id', '=', $id)->delete();
						$transfers = DB::table('transfers')->where('material_id', '=', $id)->delete();
						$results = array(
							"status" => true,
							"message" => "Material berhasil dihapus"
						);
						return Response::json($results);
					}
				}
				else {
					$results = array(
						"status" => false,
						"message" => "Transfer tidak terdaftar"
					);
					return Response::json($results);
				}
			}
			else {
				$results = array(
					"status" => false,
					"message" => "Transfer tidak terdaftar"
				);
				return Response::json($results);
			}
		}
		catch(ModelNotFoundException $e) {
			$results = array(
				"success" => false,
				"message" => "Hapus transfer gagal"
			);
			return Response::json($results);
		}
		// $iTotalRecords = DB::table('materials')->whereNull('deleted_at')->count();
		// $aaData = DB::table('materials')->whereNull('deleted_at')->skip($iDisplayStart)->take($iDisplayLength)->orderBy('material_number', 'asc')->get();
		// $results = array(
		// 	"iTotalRecords" => $iTotalRecords,
		// 	"iTotalDisplayRecords" => $iTotalRecords,
		// 	"aaData" => $aaData
		// );
		// return Response::json($results);
	}

	/**
	 * Get Inventory Lot by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Inventory
	 *
	 */

	function getInventoryLot($material_number) {
		$inventories = DB::table('inventories')->where('material_number', $material_number)->first();
		return $inventories;
	}

}