<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompletionController extends BaseController {

	public function openFilterListPage() {
		return View::make('completions.filter', array(
			'page' => 'completions'
		));
	}

	public function openFilteredListPage() {
		$data = Input::all();
		$completions = self::getFilterCompletions($data);
		$fullURL = Request::fullUrl();
		$query = explode("?", $fullURL);
		return View::make('completions.filter-list', array(
			'page' => 'completions',
			'completions' => $completions,
			'parameter' => $query[1]
		));
	}

	function getFilterCompletions($data) {
		$completionsTable = DB::table('completions')->join('materials', 'completions.material_id', '=', 'materials.id');
		if (isset($data['barcode_number']) && strlen($data['barcode_number']) > 0) {
			if (isset($data['barcode_state'])) {
				if ($data['barcode_state'] == "contain") {
					$completionsTable = $completionsTable->where('completions.barcode_number', 'LIKE', '%' . $data['barcode_number'] . '%');
				}
				else {
					$completionsTable = $completionsTable->where('completions.barcode_number', '=', $data['barcode_number']);
				}
			}
			else {
				$completionsTable = $completionsTable->where('completions.barcode_number', '=', $data['barcode_number']);
			}
		}
		if (isset($data['material_numbers']) && strlen($data['material_numbers']) > 0) {
			$material_numbers = explode(",", $data['material_numbers']);
			$completionsTable = $completionsTable->whereIn('materials.material_number', $material_numbers);
		}
		if (isset($data['locations']) && strlen($data['locations']) > 0) {
			$locations = explode(",", $data['locations']);
			$completionsTable = $completionsTable->whereIn('materials.location', $locations);
		}
		if (isset($data['description']) && strlen($data['description']) > 0) {
			$completionsTable = $completionsTable->where('materials.description', 'LIKE', '%' . $data['description'] . '%');
		}

		// lot_completion
		if (isset($data['lot_from']) && strlen($data['lot_from']) > 0 && isset($data['lot_until']) && strlen($data['lot_until']) > 0) {
			$from = intval($data['lot_from']);
			$until = intval($data['lot_until']);
			if ($from != $until) {
				$completionsTable = $completionsTable->whereBetween('completions.lot_completion', array($from, $until));
			}
			else {
				$completionsTable = $completionsTable->where('completions.lot_completion', '=', $from);	
			}
		}
		if (isset($data['active']) && strlen($data['active']) > 0) {
			$completionsTable = $completionsTable->where('completions.active', '=', $data['active']);
		}
		$completions = $completionsTable
		->select(
			'completions.id',
			'completions.barcode_number',
			'completions.issue_plant',
			'completions.lot_completion',
			'materials.material_number',
			'materials.location',
			'materials.description',
			'completions.limit_used',
			'completions.active',
			'completions.issue_plant',
			'completions.created_at',
			'completions.updated_at'
		)
		->orderBy('completions.id', 'asc')->get();

		return $completions;
	}

	public function activeAllFilteredCompletion() {
		$fullURL = Request::fullUrl();
		$url = Request::url();
		$queryString = str_replace(Request::url(), "", $fullURL);
		$url = Request::root() . "/completions/list/filter/list" . $queryString;
		// echo Request::path();
		$data = Input::all();
		$completions = self::getFilterCompletions($data);
		foreach ($completions as $completion) {
			DB::table('completions')->where('id', '=', $completion->id)->update(array('active' => 1));
		}
		return Redirect::to($url);
	}

	public function nonactiveAllFilteredCompletion() {
		$fullURL = Request::fullUrl();
		$url = Request::url();
		$queryString = str_replace(Request::url(), "", $fullURL);
		$url = Request::root() . "/completions/list/filter/list" . $queryString;
		// echo Request::path();
		$data = Input::all();
		$completions = self::getFilterCompletions($data);
		foreach ($completions as $completion) {
			DB::table('completions')->where('id', '=', $completion->id)->update(array('active' => 0));
		}
		return Redirect::to($url);
	}

	public function deleteAllFilteredCompletion() {
		$fullURL = Request::fullUrl();
		$url = Request::url();
		$queryString = str_replace(Request::url(), "", $fullURL);
		$url = Request::root() . "/completions/list/filter/list" . $queryString;
		// echo Request::path();
		$data = Input::all();
		$completions = self::getFilterCompletions($data);
		foreach ($completions as $completion) {
			DB::table('completions')->where('id', '=', $completion->id)->delete();
			DB::table('transfers')->where('completion_id', '=', $completion->id)->delete();
		}
		return Redirect::to($url);
	}

	/**
	 * Display a list products page
	 *
	 * @return View
	 *
	 */
	
	public function openListPage() {
		// $completions = Completion::with(array('material', 'user'))
							// ->orderBy('barcode_number', 'ASC')
							// ->get();
		$completions = Completion::count();
		return View::make('completions.list', array(
			'page' => 'completions',
			'completions' => $completions
		));
	}

	/**
	 * Display a add product page
	 *
	 * @return View
	 *
	 */

	public function openAddPage() {
		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('completions.add', array(
			'page' => 'completions',
			'materials' => $materials
		));
	}

	/**
	 * Save product
	 *
	 * @return
	 *
	 */

	public function createCompletion() {

		// Completion
		$data = Input::all();
		$material = self::getMaterialById($data['material_id']);
		$validator = Validator::make($data, Completion::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data["user_id"] = Session::get('id');
		$completion = Completion::create($data);

		return Redirect::route('listcompletions');
	}

	/**
	 * Display a detail product page
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 *
	 */

	public function openDetailPage($id) {
		try {
			$completion = Completion::findOrFail($id);
			return View::make('completions.detail', array(
				'page' => 'completions',
				'completion' => $completion
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit product page
	 *
	 * @return View
	 *
	 */

	public function openEditPage($id) {
		try {
			$completion = Completion::findOrFail($id);
			$materials = Material::orderBy('material_number', 'ASC')->get();
			return View::make('completions.edit', array(
				'page' => 'completions',
				'completion' => $completion,
				'materials' => $materials
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
	 * @return 	View
	 *
	 */

	public function updateCompletion($id) {

		try {
			// Product
			$completion = Completion::findOrFail($id);
			$data = Input::all();

			$validator = Validator::make($data, Completion::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$data["user_id"] = Session::get('id');
			$completion->update($data);
			return Redirect::route('listcompletions');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete product
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 *
	 */

	public function deleteCompletion($id) {

		try {
			// Product
			$completion = Completion::findOrFail($id);
			$completion->delete();
			return Redirect::route('listcompletions');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}












	/**
	 * Display a add adjustment completions page
	 *
	 * @return View
	 *
	 */

	public function openAdjustmentPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		return View::make('completions-adjustment.add', array(
			'page' => 'completion_adjustment',
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Completion Adjustment by barcode
	 *
	 * @param Barcode 	barcode number
	 *
	 * @return Response (status, status_code, message, barcode)
	 *
	 */

	function completionAdjustment($barcode, $user_id) {//, $posting_date) {



		$data['user_id'] = $user_id;
		$data['barcode_number'] = $barcode;
		$completion = self::getCompletion($data["barcode_number"]);

		// Check barcode is registered

		if ($completion == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar.",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode is active

		if ($completion->active == 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1001,
				'message' => "Barcode tidak aktif.",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode on inventory

		// $inventory = self::getInventoryLot($completion->material_number);
		$inventory = self::getInventoryLotByBarcode($data["barcode_number"]);
		if ($inventory == null) {
			// insert here
			$data["material_number"] = $completion->material_number;
			$data["lot"] = $completion->lot_completion;
			$data['last_action'] = "completion_adjustment";
			$data["description"] = $completion->description;
			$data["issue_location"] = $completion->location;
			Inventory::create($data);

			$history['category'] = "completion_adjustment";
			$history['completion_barcode_number'] = $completion->barcode_number;
			$history['completion_description'] = $completion->description;
			$history['completion_location'] = $completion->location;
			$history['completion_issue_plant'] = $completion->issue_plant;
			$history['completion_material_id'] = $completion->material_id;
			$history['lot'] = $completion->lot_completion;
			$history['synced'] = 0;
			// $history['created_at'] = $posting_date;
			// $history['updated_at'] = $posting_date;
			if (isset($data['user_id'])) {
				$history['user_id'] = $data['user_id'];
			}
			History::create($history);

			$history['completion_barcode_number'] = $completion->barcode_number;
			$response = array(
				'status' => true, 
				'message' => "Barcode berhasil di completion.",
				'data' => json_encode($history),
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode lot in inventory

		if ($inventory->lot > 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Jumlah pada inventory masih tersedia " . $inventory->lot . " dikarenakan transfer belum dilakukan.",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode lead time

		$nowTimestamp = self::getTimestamp();
		$completionTimestamp = $completion->lead_time;
		$leadTime = $completionTimestamp * 60;
		$lastScanTimestamp = strtotime($inventory->updated_at);


		//soldering_db

		if($completion->location == 'SX21' && $completion->category == 'KEY'){
			try{
				$m_hsa_kartu = db::connection('welding')->table('m_hsa_kartu')->where('hsa_kartu_barcode', '=', $completion->barcode_number)->first();

				$tes = DB::connection('welding')
				->table('t_order')
				->where('part_type', '=', '2')
				->where('order_status', '=', '5')
				->where('part_id', '=', $m_hsa_kartu->hsa_id)
				->where('kanban_no', '=', $m_hsa_kartu->hsa_kartu_no)
				->update([
					'order_status' => '3'
				]);

				$del = DB::connection('mysql2')
				->table('welding_inventories')
				->where('welding_inventories.barcode_number', '=', $completion->barcode_number)
				->delete();

				$del2 = DB::connection('welding')
				->table('t_cuci')
				->where('kartu_code', '=', $m_hsa_kartu->hsa_kartu_code)
				->delete();
			}
			catch(\Exception $e){
				// $response = array(
				// 	'status' => false, 
				// 	'status_code' => 1003,
				// 	'message' => $e->getMessage(),
				// 	'barcode' => $barcode
				// );
				// return $response;
			}
		}

		if($completion->category == 'PART PROCESS' && $completion->limit_used != 1 && substr($completion->barcode_number, 0, 4) != 'BLCR'){
			try{
				$tes = DB::connection('initial')
				->table('t_cs')
				->insert([
					'kitto_code' => $completion->material_number,
					'quantity' => $completion->lot_completion,
					'no_kanban' => substr($completion->barcode_number, 11),
					'pesanan_status' => "0",
					'pesanan_create_date' => date( 'Y-m-d H:i:s'),
					'is_finish' => 1
				]);
			}
			catch(\Exception $e){
				// $response = array(
				// 	'status' => false, 
				// 	'status_code' => 1003,
				// 	'message' => $e->getMessage()
				// );
				// return Response::json($response);
			}
		}

		// if (($lastScanTimestamp + $leadTime) >= $nowTimestamp) {
		// 	$response = array(
		// 		'status' => false, 
		// 		'status_code' => 1002,
		// 		'message' => "Barcode tidak dapat di completion karena masih dalam waktu lead time.",
		// 		'barcode' => $barcode
		// 	);
		// 	return $response;
		// }

		$data["material_number"] = $completion->material_number;
		$data["lot"] = $inventory->lot + $completion->lot_completion;
		$data['last_action'] = "completion_adjustment";
		$data["issue_location"] = $completion->location;
		self::updateInventory($inventory->id, $data);

		// Deactived barcode if limit used

		// if ($completion->limit_used == 1) {
		// 	self::deactivedCompletion($completion->id);
		// }

		$history['category'] = "completion_adjustment";
		$history['completion_barcode_number'] = $completion->barcode_number;
		$history['completion_description'] = $completion->description;
		$history['completion_location'] = $completion->location;
		$history['completion_issue_plant'] = $completion->issue_plant;
		$history['completion_material_id'] = $completion->material_id;
		$history['lot'] = $completion->lot_completion;
		$history['synced'] = 0;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);


		

		if($completion->location == 'SX51' && $completion->category == 'KEY'){
			try{
				$tes = DB::connection('mysql2')
				->table('middle_inventories')
				->where('middle_inventories.tag', '=', $completion->barcode_number)
				->delete();
			}
			catch(\Exception $e){	
			}
			try{
				$tes2 = DB::connection('mysql2')
				->table('barrel_queues')
				->where('barrel_queues.tag', '=', $completion->barcode_number)
				->delete();
			}
			catch(\Exception $e){
			}
			try{
				$tes3 = DB::connection('mysql2')
				->table('barrels')
				->where('barrels.tag', '=', $completion->barcode_number)
				->where('barrels.machine', '=', 'FLANEL')
				->delete();
			}
			catch(\Exception $e){	
			}
		}

		$response = array(
			'status' => true, 
			'message' => "Completion berhasil dilakukan",
			'data' => json_encode($history),
			'barcode' => $barcode
		);
		return $response;
	}

	/**
	 * Display a add adjustment completions excel page
	 *
	 * @return View
	 *
	 */

	public function openAdjustmentExcelPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		return View::make('completions-adjustment.excel', array(
			'page' => 'completion_adjustment_excel',
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Adjustment Completion and display success and failed page
	 * Adjustment Copy Barcode Numbers from Excel
	 *
	 * @return View
	 *
	 */

	public function completionAdjustmentFromExcel() {

		$data = Input::all();
		// echo json_encode($data);
		// exit();
		$barcodes = explode(",", $data['barcode_numbers']);
		// $date = $data['date'];
		// $time = $data['time'];
		// $datetime = $date . " " . $time;
		// $phpDate = date_create_from_format('m/d/Y H:i', $datetime);
		// $posting_date = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
		$success = array();
		$failed = array();
		if (count($barcodes) > 0) {
			for ($i = 0; $i < count($barcodes); $i++) {
				$barcode = $barcodes[$i];
				// $response = self::completionAdjustment($barcode, $data['user_id'], $posting_date);
				$response = self::completionAdjustment($barcode, $data['user_id']);
				if ($response['status'] == true) {
					// $adjustment = json_decode($response['data']);
					// $history['category'] = "completion_adjustment";
					// $history['completion_barcode_number'] = $adjustment->completion_barcode_number;
					// $history['completion_description'] = $adjustment->completion_description;
					// $history['completion_location'] = $adjustment->completion_location;
					// $history['completion_issue_plant'] = $adjustment->completion_issue_plant;
					// $history['completion_material_id'] = $adjustment->completion_material_id;
					// $history['lot'] = $adjustment->lot;
					// $history['synced'] = 0;
					// if (isset($data['user_id'])) {
					// 	$history['user_id'] = $data['user_id'];
					// }
					// History::create($history);
					array_push($success,  $response);
				}
				else {
					array_push($failed,  $response);
				}
			}
		}

		$response = array(
			'success' => $success,
			'failed' => $failed 
		);

		return View::make('completions-adjustment.list', array(
			'page' => 'completion_adjustment_excel',
			'category' => 'excel',
			'adjustments' => $response
		));
	}

	/**
	 * Display a add adjustment completions manual page
	 *
	 * @return View
	 *
	 */

	public function openAdjustmentManualPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('completions-adjustment.manual', array(
			'page' => 'completion_adjustment_manual',
			'materials' => $materials,
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Adjustment Completion and display success and failed page
	 * Adjustment Manual
	 *
	 * @return View
	 *
	 */

	public function completionAdjustmentManual() {

		$data = Input::all();
		$date = $data['date'];
		$time = $data['time'];
		$datetime = $date . " " . $time;
		$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
		$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
		$data["created_at"] = $mysqlDate;
		$validator = Validator::make($data, CompletionAdjustment::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data["user_id"] = Session::get('id');
		$data["active"] = 1;
		unset($data['date']);
		unset($data['time']);
		unset($data['hour']);
		unset($data['minute']);

		$completion = CompletionAdjustment::create($data);
		$material = self::getMaterialById($completion->material_id);

		if ($completion && isset($material)) {
			$history['category'] = "completion_adjustment";
			$history['completion_location'] = $material->location;
			$history['completion_issue_plant'] = $completion->issue_plant;
			$history['completion_material_id'] = $completion->material_id;
			$history['completion_reference_number'] = $completion->reference_number;
			$history['lot'] = $completion->lot_completion;// * -1;
			$history['synced'] = 0;
			$history['created_at'] = $mysqlDate;
			if (isset($data['user_id'])) {
				$history['user_id'] = $data['user_id'];
			}
			History::create($history);
		}

		return View::make('completions-adjustment.list', array(
			'page' => 'completion_adjustment',
			'category' => 'manual',
			'adjustments' => $completion
		));

	}

	/**
	 * Open Scan Completion page
	 *
	 * @return View
	 *
	 */

	public function openCompletionPage() {
		return View::make('completions.scan');
	}

	/**
	 * AJAX Completion
	 *
	 * @return JSON 	response (status, status code, message)
	 *
	 */

	public function completionItem() {

		$data = Input::all();
		$completion = self::getCompletion($data["barcode_number"]);

		// Check barcode is registered

		if ($completion == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar."
			);
			return Response::json($response);
		}

		// Check barcode is active

		if ($completion->active == 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1001,
				'message' => "Barcode tidak aktif."
			);
			return Response::json($response);
		}



		// Check barcode on inventory

		// $inventory = self::getInventoryLot($completion->material_number);
		$inventory = self::getInventoryLotByBarcode($data["barcode_number"]);
		if ($inventory == null) {
			// insert here
			$data["material_number"] = $completion->material_number;
			$data["lot"] = $completion->lot_completion;
			$data["last_action"] = "completion";
			$data["description"] = $completion->description;
			$data["issue_location"] = $completion->location;
			Inventory::create($data);

			$history['category'] = "completion";
			$history['completion_barcode_number'] = $completion->barcode_number;
			$history['completion_description'] = $completion->description;//$completion->description_completion;
			$history['completion_location'] = $completion->location;
			$history['completion_issue_plant'] = $completion->issue_plant;
			$history['completion_material_id'] = $completion->material_id;
			$history['lot'] = $completion->lot_completion;
			$history['synced'] = 0;
			if (isset($data['user_id'])) {
				$history['user_id'] = $data['user_id'];
			}
			History::create($history);

			$history['completion_barcode_number'] = $completion->barcode_number;
			$response = array(
				'status' => true, 
				'message' => "Completion berhasil dilakukan.",
				'data' => json_encode($history)
			);
			return Response::json($response);
		}

		// $turnOver = self::getTurnOverByBarcodeNumber($data['barcode_number']);
		// if ($turnOver == null) {
		// 	$turnover["barcode_number"] = $data["barcode_number"];
		// 	$turnover["material_number"] = $completion->material_number;
		// 	$turnover["cycle"] = 0;
		// 	TurnOver::create($$turnover);
		// }

		// Check barcode lot in inventory

		if ($inventory->lot > 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Jumlah pada inventory masih tersedia " . $inventory->lot . " dikarenakan transfer belum dilakukan"
			);
			return Response::json($response);
		}

		// Check barcode lead time
		$lastHistory = Inventory::select('updated_at')->where('barcode_number', '=', $data['barcode_number'])->first();
		if ($lastHistory != null) {
			// if ($inventory->last_action == "transfer" || $inventory->last_action == "transfer_adjustment") {
			$nowTimestamp = self::getTimestamp();
			$completionTimestamp = $completion->lead_time;
			$leadTime = $completionTimestamp * 60;
			$lastScanTimestamp = strtotime($lastHistory->updated_at);

			if (($lastScanTimestamp + $leadTime) >= $nowTimestamp) {
				$response = array(
					'status' => false, 
					'status_code' => 1002,
					'message' => "Barcode tidak dapat di completion karena masih dalam waktu lead time."
				);
				return Response::json($response);
			}	
			// }
		}

		//soldering_db

		if($completion->location == 'SX21' && $completion->category == 'KEY'){
			try{
				$m_hsa_kartu = db::connection('welding')->table('m_hsa_kartu')->where('hsa_kartu_barcode', '=', $completion->barcode_number)->first();

				$tes = DB::connection('welding')
				->table('t_order')
				->where('part_type', '=', '2')
				->where('order_status', '=', '5')
				->where('part_id', '=', $m_hsa_kartu->hsa_id)
				->where('kanban_no', '=', $m_hsa_kartu->hsa_kartu_no)
				->update([
					'order_status' => '3'
				]);

				$del = DB::connection('mysql2')
				->table('welding_inventories')
				->where('welding_inventories.barcode_number', '=', $completion->barcode_number)
				->delete();

				$del2 = DB::connection('welding')
				->table('t_cuci')
				->where('kartu_code', '=', $m_hsa_kartu->hsa_kartu_code)
				->delete();
			}
			catch(\Exception $e){
				// $response = array(
				// 	'status' => false, 
				// 	'status_code' => 1003,
				// 	'message' => $e->getMessage(),
				// 	'barcode' => $barcode
				// );
				// return $response;
			}
		}

		if($completion->category == 'PART PROCESS' && $completion->limit_used != 1 && substr($completion->barcode_number, 0, 4) != 'BLCR'){
			try{
				$tes = DB::connection('initial')
				->table('t_cs')
				->insert([
					'kitto_code' => $completion->material_number,
					'quantity' => $completion->lot_completion,
					'no_kanban' => substr($completion->barcode_number, 11),
					'pesanan_status' => "0",
					'pesanan_create_date' => date( 'Y-m-d H:i:s'),
					'is_finish' => 1
				]);
			}
			catch(\Exception $e){
				// $response = array(
				// 	'status' => false, 
				// 	'status_code' => 1003,
				// 	'message' => $e->getMessage()
				// );
				// return Response::json($response);
			}
		}

		$data["material_number"] = $completion->material_number;
		$data["lot"] = $inventory->lot + $completion->lot_completion;
		$data["last_action"] = "completion";
		$data["description"] = $completion->description;
		$data["issue_location"] = $completion->location;
		self::updateInventory($inventory->id, $data);

		// Deactived barcode if limit used

		// if ($completion->limit_used == 1) {
		// 	self::deactivedCompletion($completion->id);
		// }

		$history['category'] = "completion";
		$history['completion_barcode_number'] = $completion->barcode_number;
		$history['completion_description'] = $completion->description; //$completion->description_completion;
		$history['completion_location'] = $completion->location;
		$history['completion_issue_plant'] = $completion->issue_plant;
		$history['completion_material_id'] = $completion->material_id;
		$history['lot'] = $completion->lot_completion;
		$history['synced'] = 0;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);

		

		if($completion->location == 'SX51' && $completion->category == 'KEY'){
			$final = DB::connection('mysql2')
			->table('assembly_key_inventories')
			->where('assembly_key_inventories.tag', '=', $completion->barcode_number)
			->first();

			$middle = DB::connection('mysql2')
			->table('middle_inventories')
			->where('middle_inventories.tag', '=', $completion->barcode_number)
			->first();

			try{
				if($final){
					$update = DB::connection('mysql2')
					->table('assembly_key_inventories')
					->where('assembly_key_inventories.tag', '=', $completion->barcode_number)
					->update([
						'material_number' => $middle->material_number,
						'location' => 'stockroom',
						'quantity' => $middle->quantity,
						'remark' => $middle->remark,
						'last_check' => $middle->last_check,
						'updated_at' => date( 'Y-m-d H:i:s')
					]);

				}else{
					$insert = DB::connection('mysql2')
					->table('assembly_key_inventories')
					->insert([
						'tag' => $middle->tag,
						'material_number' => $middle->material_number,
						'location' => 'stockroom',
						'quantity' => $middle->quantity,
						'remark' => $middle->remark,
						'last_check' => $middle->last_check,
						'created_at' => date( 'Y-m-d H:i:s'),
						'updated_at' => date( 'Y-m-d H:i:s')
					]);
				}				
			}
			catch(\Exception $e){
				
			}


			try{
				$delete = DB::connection('mysql2')
				->table('middle_inventories')
				->where('middle_inventories.tag', '=', $completion->barcode_number)
				->delete();
			}
			catch(\Exception $e){
				
			}







			try{

				$tes2 = DB::connection('mysql2')
				->table('barrel_queues')
				->where('barrel_queues.tag', '=', $completion->barcode_number)
				->delete();

			}
			catch(\Exception $e){
				
			}
			try{
				$tes3 = DB::connection('mysql2')
				->table('barrels')
				->where('barrels.tag', '=', $completion->barcode_number)
				->where('barrels.machine', '=', 'FLANEL')
				->delete();

			}
			catch(\Exception $e){
				
			}
		}

		$response = array(
			'status' => true, 
			'message' => "Completion berhasil dilakukan",
			'data' => json_encode($history)
		);
		return Response::json($response);

	}

	/**
	 * Open Cancel Completion page
	 *
	 * @return View
	 *
	 */

	public function openCancelPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		return View::make('completions-cancel.add', array(
			'page' => 'completion_cancel',
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Cancel Completion item
	 *
	 * @return View
	 *
	 */

	public function cancelCompletionItem() {

		$data = Input::all();
		$completion = self::getCompletion($data["barcode_number"]);

		// Check barcode is registered

		if ($completion == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar."
			);
			return Response::json($response);
		}

		// Check barcode is active

		if ($completion->active == 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1001,
				'message' => "Barcode tidak aktif."
			);
			return Response::json($response);
		}

		// Check barcode on inventory

		// $inventory = self::getInventoryLot($completion->material_number);
		$inventory = self::getInventoryLotByBarcode($data["barcode_number"]);
		if ($inventory == null) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Barcode tidak dapat di cancel completion karena completion belum dilakukan."
			);
			return Response::json($response);
		}

		// Check barcode lot in inventory

		if ($inventory->lot < $completion->lot_completion) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Jumlah pada inventory tidak tersedia"
			);
			return Response::json($response);
		}

		$data["material_number"] = $completion->material_number;
		$data["lot"] = $inventory->lot - $completion->lot_completion;
		$data["last_action"] = "completion_cancel";
		self::updateInventory($inventory->id, $data);

		// Deactived barcode if limit used

		// if ($completion->limit_used == 1) {
		// 	self::deactivedCompletion($completion->id);
		// }

		$history['category'] = "completion_cancel";
		$history['completion_barcode_number'] = $completion->barcode_number;
		$history['completion_description'] = $completion->description; //$completion->description_completion;
		$history['completion_location'] = $completion->location;
		$history['completion_issue_plant'] = $completion->issue_plant;
		$history['completion_material_id'] = $completion->material_id;
		$history['lot'] = $completion->lot_completion * -1;
		$history['synced'] = 0;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);

		if($completion->location == 'SX51' && $completion->category == 'KEY'){
			try{
				$tes = DB::connection('mysql2')
				->table('middle_inventories')
				->insert([
					'tag' => $completion->barcode_number,
					'material_number' => $completion->material_number,
					'location' => $completion->surface.'-kensa',
					'quantity' => $completion->lot_completion,
					'remark' => 'cancel-completion',
					'created_at' => date( 'Y-m-d H:i:s'),
					'updated_at' => date( 'Y-m-d H:i:s')
				]);
			}
			catch(\Exception $e){
			}
		}

		$response = array(
			'status' => true, 
			'message' => "Cancel Completion berhasil dilakukan",
			'data' => json_encode($history)
		);
		return Response::json($response);

	}

	/**
	 * Open History Page
	 *
	 * @return View
	 *
	 */

	public function openHistoryPage() {

		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('completions.history-filter', array(
			'page' => 'completion_history',
			'materials' => $materials
		));
	}

	/**
	 * Filter Completion History
	 *
	 * @return View
	 *
	 */

	public function filterCompletionHistory() {

		$data = Input::all();
		$historiesTable = DB::table('histories')
		->join('materials', 'histories.completion_material_id', '=', 'materials.id')
		->leftJoin('users', 'histories.user_id', '=', 'users.id')
		->select(
			'histories.id',
			DB::raw('(CASE histories.category
				WHEN "completion" THEN "Completion"
				WHEN "completion_adjustment" THEN "Completion Adjustment"
				WHEN "completion_adjustment_excel" THEN "Completion Excel"
				WHEN "completion_adjustment_manual" THEN "Completion Manual"
				WHEN "completion_cancel" THEN "Completion Cancel"
				WHEN "completion_return" THEN "Completion Return"
				WHEN "completion_repair" THEN "Completion Repair"
				WHEN "completion_after_repair" THEN "Completion After Repair"
				WHEN "completion_error" THEN "Completion Error"
				WHEN "completion_temporary_delete" THEN "Completion Temporary"
				ELSE "Unidentified" END) AS category'),
			'histories.completion_barcode_number',
			'histories.completion_description',
			'histories.completion_location',
			'histories.completion_issue_plant',
			'histories.lot',
			'materials.material_number',
			'materials.location',
			'materials.description',
			'users.name',
			'histories.created_at',
			'histories.reference_file',
			'histories.updated_at'
		);

		if (isset($data['start_date']) && strlen($data['start_date']) > 0) {
			$date = $data['start_date'];
			$start_date_time;
			if (isset($data['start_time']) && strlen($data['start_time']) > 0) {
				$time = $data['start_time'];
				$datetime = $date . " " . $time;
				$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
				$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
				$start_date_time = $mysqlDate;
			}
			else {
				$time = "00:00";
				$datetime = $date . " " . $time;
				$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
				$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
				$start_date_time = $mysqlDate;
			}
			$historiesTable = $historiesTable->where('histories.created_at', '>=', $start_date_time);
		}
		if (isset($data['end_date']) && strlen($data['end_date']) > 0) {
			$date = $data['end_date'];
			$end_date_time;
			if (isset($data['end_time']) && strlen($data['end_time']) > 0) {
				$time = $data['end_time'];
				$datetime = $date . " " . $time;
				$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
				$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
				$end_date_time = $mysqlDate;
			}
			else {
				$time = "23:59:59";
				$datetime = $date . " " . $time;
				$phpDate = date_create_from_format('m/d/Y H:i:s', $datetime);
				$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
				$end_date_time = $mysqlDate;
			}
			$historiesTable = $historiesTable->where('histories.created_at', '<=', $end_date_time);
		}
		if (isset($data['barcode_number']) && strlen($data['barcode_number']) > 0) {
			$barcodes = explode(",", $data['barcode_number']);
			$historiesTable = $historiesTable->whereIn('histories.completion_barcode_number', $barcodes);
		}
		if (isset($data['material_number']) && strlen($data['material_number']) > 0) {
			$materials = explode(",", $data['material_number']);
			$materialsIDsz = DB::table('materials')->select('id')->whereIn('material_number', $materials)->get();
			$materialsIDs = Array();
			foreach ($materialsIDsz as $object) {
				array_push($materialsIDs, $object->id);
			}
			$historiesTable = $historiesTable->whereIn('histories.completion_material_id', $materialsIDs);
		}
		if (isset($data['location_completion']) && strlen($data['location_completion']) > 0) {
			$locations = explode(",", $data['location_completion']);
			$historiesTable = $historiesTable->whereIn('histories.completion_location', $locations);
		}
		if (isset($data['category']) && strlen($data['category']) > 0) {
			$categories = explode(",", $data['category']);
			$historiesTable = $historiesTable->where('histories.category','=', $categories);
		}
		$histories = $historiesTable
		->whereIn('histories.category', array(
			'completion', 
			'completion_adjustment', 
			'completion_adjustment_excel', 
			'completion_adjustment_manual', 
			'completion_cancel', 
			'completion_error',
			'completion_return',
			'completion_repair',
			'completion_after_repair',  
			'completion_temporary_delete'
		)
	)
		->orderBy('histories.created_at', 'asc')
		->get();

		if(isset($data['export'])) {
			$data = array(
				'histories' => $histories
			);
			Excel::create('History Completions', function($excel) use ($data){
				$excel->sheet('History', function($sheet) use ($data) {
					return $sheet->loadView('completions.history-excel', $data);
				});
			})->export('xls');
		}
		else {
			return View::make('completions.history', array(
				'page' => 'completions_history',
				'histories' => $histories,
				'data' => $data
			));
		}
	}

	/**
	 * Export master completions to Excel File
	 *
	 * @return Ms. Excel File
	 *
	 */

	public function exportToExcel() {

		$completions;
		$data = Input::all();
		if (isset($data) && count($data) > 0) {
			$completions = self::getFilterCompletions($data);
		}
		else {
			$completions = DB::table('completions')
			->join('materials', 'materials.id', '=', 'completions.material_id')
			->select( 
				'completions.barcode_number', 
				'materials.material_number',
				'materials.location', 
				'completions.issue_plant', 
				'completions.lot_completion', 
				'completions.limit_used', 
				'completions.active'
			)
			->orderBy('barcode_number', 'ASC')
			->get();
		}
		$data = array(
			'completions' => $completions
		);
		Excel::create('Master Completion', function($excel) use ($data){
			$excel->sheet('Master', function($sheet) use ($data) {
				return $sheet->loadView('completions.excel', $data);
			});
		})->export('xls');
	}

	/**
	 * Export completion histories to Excel File
	 *
	 * @return Ms. Excel File
	 *
	 */

	public function exportHistoryToExcel() {
		self::filterCompletionHistory();
	}

	/**
	 * Import completion master from Excel File
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
					if ($material != null) {
						$data['barcode_number'] = $row->barcode_number;
						$data['material_id'] = $material->id;
						// $data['description_completion'] = $row->description;
						// $data['location_completion'] = $row->location;
						$data['issue_plant'] = $row->plant;
						$data['lot_completion'] = $row->lot;
						$data['limit_used'] = $row->limit;
						if (strtolower($row->status) == "active") {
							$data['active'] = 1;	
						}
						else {
							$data['active'] = 0;
						}
						$data["user_id"] = Session::get('id');
						if ($row->barcode_number != null) {
							$existedCompletion = Completion::where("barcode_number", "=", $row->barcode_number)->first();
							if ($existedCompletion == null) {
								$completion = Completion::create($data);
							}
						}

						try{
							if($material->category == 'KD-TPRO'){
								$tes = DB::connection('initial')
								->table('t_kd')
								->insert([
									'kitto_code' => $row->material_number,
									'quantity' => $row->lot,
									'no_kanban' => substr($row->barcode_number, 11),
									'pesanan_status' => "0",
									'pesanan_create_date' => date( 'Y-m-d H:i:s'),
									'is_finish' => 1
								]);							
							}
						}
						catch(\Exception $e){
							// $response = array(
							// 	'status' => false, 
							// 	'status_code' => 1003,
							// 	'message' => $e->getMessage()
							// );
							// return Response::json($response);
						}						
					}
				});
			});
			return Redirect::route('listcompletions');
		}
	}

	function openTemporaryPage() {

		$temporaries = DB::table('histories')
		->join('materials', 'histories.completion_material_id', '=', 'materials.id')
		->where('histories.synced', '=', 0)
		->whereNull('histories.deleted_at')
					// ->whereIn('histories.category', array('completion', 'completion_adjustment', 'completion_adjustment_excel', 'completion_adjustment_manual', 'completion_cancel', 'completion_return', 'completion_error'))
		->whereIn('histories.category', array('completion', 'completion_adjustment', 'completion_adjustment_excel', 'completion_adjustment_manual', 'completion_cancel', 'completion_error', 'completion_temporary_delete', 'completion_return', 'completion_repair', 'completion_after_repair'))
					// ->whereIn('histories.category', array('completion_adjustment_manual', 'completion_cancel', 'completion_error', 'completion_temporary_delete'))
		->select(
						// DB::raw('(CASE histories.category
						// 	WHEN "completion" THEN "Completion"
						// 	WHEN "completion_adjustment" THEN "Completion Adjustment"
						// 	WHEN "completion_adjustment_excel" THEN "Completion Excel"
						// 	WHEN "completion_adjustment_manual" THEN "Completion Manual"
						// 	WHEN "completion_cancel" THEN "Completion Cancel"
						// 	WHEN "completion_return" THEN "Completion Return"
						// 	WHEN "completion_repair" THEN "Completion Repair"
						// 	WHEN "completion_after_repair" THEN "Completion After Repair"
						// 	WHEN "completion_error" THEN "Completion Error"
						// 	WHEN "completion_temporary_delete" THEN "Completion Temporary"
						// 	ELSE "Unidentified" END) AS category'),
			'histories.category', 
						// 'histories.completion_barcode_number', 
			'materials.description as completion_description', 
			'histories.completion_location', 
			'histories.completion_issue_plant', 
			'histories.completion_material_id',
						// 'histories.completion_reference_number',
			'materials.material_number',
			DB::raw('SUM(histories.lot) as lot'), 
						// 'histories.lot', 
						// 'histories.synced', 
						// 'histories.user_id', 
						// 'histories.deleted_at', 
			'histories.created_at'
						// 'histories.updated_at',
						// 'histories.reference_file',
						// 'histories.error_description'
		)
		->groupBy(
			'histories.completion_material_id'
		)
		->having(DB::raw('SUM(histories.lot)'), '<', 0)
		->get();

        // echo json_encode($temporaries);
        // exit();
		return View::make('completions.temporary', array(
			'page' => 'completion_temporaries',
			'temporaries' => $temporaries
		));

	}

	function deleteCompletionTemporary($id) {
		// $temporary = DB::table('histories')
		// ->where('completion_material_id', '=', $id)
		// ->where('histories.synced', '=', 0)
		// ->whereIn('histories.category', array(
		// 	'completion', 
		// 	'completion_adjustment', 
		// 	'completion_adjustment_excel', 
		// 	'completion_adjustment_manual', 
		// 	'completion_cancel', 
		// 	'completion_error'
		// ))->first();
		$temporary = DB::table('histories')
		->join('materials', 'histories.completion_material_id', '=', 'materials.id')
		->where('histories.completion_material_id', '=', $id)
		->where('histories.synced', '=', 0)
		->whereIn('histories.category', array('completion', 'completion_adjustment', 'completion_adjustment_excel', 'completion_adjustment_manual', 'completion_cancel', 'completion_error', 'completion_temporary_delete', 'completion_return', 'completion_after_repair', 'completion_repair'))
		->select(
			'histories.category',
			'histories.completion_barcode_number', 
			'histories.completion_description', 
			'histories.completion_location', 
			'histories.completion_issue_plant', 
			'histories.completion_material_id',
			'histories.completion_reference_number',
			'materials.material_number',
			DB::raw('SUM(histories.lot) as lot'), 
						// 'histories.lot', 
			'histories.synced', 
			'histories.user_id', 
			'histories.deleted_at', 
			'histories.created_at', 
			'histories.updated_at',
			'histories.reference_file',
			'histories.error_description'
		)
		->groupBy(
			'histories.completion_material_id'
		)
		->having(DB::raw('SUM(histories.lot)'), '<', 0)
		->first();

		// echo json_encode($temporary);
		// exit();

		if ($temporary) {
			$history = array();
			$history['category'] = "completion_temporary_delete";
			$history['completion_barcode_number'] = $temporary->completion_barcode_number;
			$history['completion_description'] = $temporary->completion_description;
			$history['completion_location'] = $temporary->completion_location;
			$history['completion_issue_plant'] = $temporary->completion_issue_plant;
			$history['completion_material_id'] = $temporary->completion_material_id;
			$history['lot'] = -1 * $temporary->lot;
			$history['synced'] = 0;
			$history['user_id'] = Session::get('id');
			History::create($history);
		}

		return Redirect::route('temporaryCompletions');
	}

	/**
	 * Get Completion by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Completion
	 *
	 */

	function getCompletion($barcode) {
		$completions = DB::table('completions')
		->leftJoin('materials', 'completions.material_id', '=', 'materials.id')
		->select(
			'completions.id', 
			'completions.barcode_number',  
			'completions.issue_plant', 
			'completions.lot_completion', 
			'completions.material_id', 
			'completions.lot_completion', 
			'completions.limit_used', 
			'completions.active', 
			'materials.material_number',
			'materials.lead_time',
			'materials.description',
			'materials.location',
			'materials.category',
			'materials.surface'
		)
		->where('completions.barcode_number', $barcode)
		->first();
		return $completions;
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

	/**
	 * Get Inventory Lot by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Inventory
	 *
	 */

	function getInventoryLotByBarcode($barcode_number) {
		$inventories = DB::table('inventories')->where('barcode_number', $barcode_number)->first();
		return $inventories;
	}

	/**
	 * Update Inventory by Barcode Number
	 *
	 * @param id 			inventory ID
	 * @param data 			inventory data
	 *
	 * @return Inventory
	 *
	 */

	function updateInventory($id, $data) {
		$date = date("Y-m-d H:i:s");
		DB::table('inventories')
		->where('id', $id)
		->update(
			array(
				'material_number' => $data['material_number'],
				'lot' => $data["lot"],
				'last_action' => $data['last_action'],
				'updated_at' => $date
			)
		);
	}

	/**
	 * Get Material by Material ID
	 *
	 * @param id 		material id
	 *
	 * @return Material
	 *
	 */

	function getMaterialById($id) {
		try {
			$material = Material::findOrFail($id);
			return $material;
		}
		catch(ModelNotFoundException $e) {
			return null;
		}
		// $completions = Completion::find;
		// return $completions;
	}

	function getTurnOverByBarcodeNumber($barcode_number) {
		$today = getdate();
		$startdate = mktime(0, 0, 0, $today['month'], $today['mday'], $today['year']);
		$enddate = mktime(23, 59, 59, $today['month'], $today['mday'], $today['year']);
		$turnOver = TurnOver::where('barcode_number', '=', $barcode_number)
		->whereBetween('created_at', array($startdate, $enddate))
		->first();
		if (isset($turnOver)) {
			return $turnOver;
		}
		else {
			return null;
		}

	}
}