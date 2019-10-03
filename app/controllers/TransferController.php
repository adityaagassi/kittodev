<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransferController extends BaseController {

	public function openFilterListPage() {
		return View::make('transfers.filter', array(
			'page' => 'transfers'
		));
	}

	public function openFilteredListPage() {
		$data = Input::all();
		$transfers = self::getFilterTransfers($data);
		$fullURL = Request::fullUrl();
		$query = explode("?", $fullURL);
		return View::make('transfers.filter-list', array(
			'page' => 'transfers',
			'transfers' => $transfers,
			'parameter' => $query[1]
		));
	}

	function getFilterTransfers($data) {
		$transfersTable = DB::table('transfers')
		->leftJoin('materials', 'transfers.material_id', '=', 'materials.id')
		->leftJoin('completions', 'transfers.completion_id', '=', 'completions.id');
		if (isset($data['barcode_transfer']) && strlen($data['barcode_transfer']) > 0) {
			if (isset($data['barcode_transfer_state'])) {
				if ($data['barcode_transfer_state'] == "contain") {
					$transfersTable = $transfersTable->where('transfers.barcode_number_transfer', 'LIKE', '%' . $data['barcode_transfer'] . '%');
				}
				else {
					$transfersTable = $transfersTable->where('transfers.barcode_number_transfer', '=', $data['barcode_transfer']);
				}
			}
			else {
				$transfersTable = $transfersTable->where('transfers.barcode_number_transfer', '=', $data['barcode_transfer']);
			}
		}
		if (isset($data['barcode_completion']) && strlen($data['barcode_completion']) > 0) {
			if (isset($data['barcode_completion_state'])) {
				if ($data['barcode_completion_state'] == "contain") {
					$transfersTable = $transfersTable->where('completions.barcode_number', 'LIKE', '%' . $data['barcode_completion'] . '%');
				}
				else {
					$transfersTable = $transfersTable->where('completions.barcode_number', '=', $data['barcode_completion']);
				}
			}
			else {
				$transfersTable = $transfersTable->where('completions.barcode_number', '=', $data['barcode_completion']);
			}
		}
		if (isset($data['material_numbers']) && strlen($data['material_numbers']) > 0) {
			$material_numbers = explode(",", $data['material_numbers']);
			$transfersTable = $transfersTable->whereIn('materials.material_number', $material_numbers);
		}
		if (isset($data['issue_locations']) && strlen($data['issue_locations']) > 0) {
			$issue_locations = explode(",", $data['issue_locations']);
			$transfersTable = $transfersTable->whereIn('transfers.issue_location', $issue_locations);
		}
		if (isset($data['receive_locations']) && strlen($data['receive_locations']) > 0) {
			$receive_locations = explode(",", $data['receive_locations']);
			$transfersTable = $transfersTable->whereIn('transfers.receive_location', $receive_locations);
		}
		if (isset($data['description']) && strlen($data['description']) > 0) {
			$transfersTable = $transfersTable->where('materials.description', 'LIKE', '%' . $data['description'] . '%');
		}

		// lot_completion
		if (isset($data['lot_from']) && strlen($data['lot_from']) > 0 && isset($data['lot_until']) && strlen($data['lot_until']) > 0) {
			$from = intval($data['lot_from']);
			$until = intval($data['lot_until']);
			if ($from != $until) {
				$transfersTable = $transfersTable->whereBetween('completions.lot_completion', array($from, $until));
			}
			else {
				$transfersTable = $transfersTable->where('completions.lot_completion', '=', $from);	
			}
		}

		$transfers = $transfersTable
		->select(
			'transfers.id',
			'transfers.barcode_number_transfer',
			'materials.material_number',
			'materials.location',
			'materials.description',
			'transfers.lot_transfer',
			'completions.barcode_number',
			'transfers.issue_location',
			'transfers.receive_location',
			'transfers.issue_plant',
			'transfers.receive_plant',
			'transfers.transaction_code',
			'transfers.movement_type',
			'transfers.reason_code'
		)
		->orderBy('transfers.id', 'asc')->get();

		return $transfers;
	}

	public function deleteAllFilteredTransfer() {
		$fullURL = Request::fullUrl();
		$url = Request::url();
		$queryString = str_replace(Request::url(), "", $fullURL);
		$url = Request::root() . "/transfers/list/filter/list" . $queryString;
		// echo Request::path();
		$data = Input::all();
		$transfers = self::getFilterTransfers($data);
		foreach ($transfers as $transfer) {
			// $material = Material::findOrFail($transfer->material_id);
			// if (isset($material)) {
				// $inventory = self::getInventoryLot($material->material_number);
			$inventory = self::getInventoryLot($transfer->barcode_number_transfer);
			if (isset($inventory)) {
				if ($inventory->lot == 0) {
					DB::table('transfers')->where('id', '=', $transfer->id)->delete();
				}
			}
			else {
				DB::table('transfers')->where('id', '=', $transfer->id)->delete();
			}
			// }
		}
		return Redirect::to($url);
	}

	/**
	 * Display a list products page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		// $transfers = Transfer::with(array('completion', 'user'))
		// 					->orderBy('barcode_number_transfer', 'ASC')
		// 					->get();
		$transfers = Transfer::count();
		return View::make('transfers.list', array(
			'page' => 'transfers',
			'transfers' => $transfers
		));
	}

	/**
	 * Display a add product page
	 *
	 * @return View
	 */

	public function openAddPage() {
		$completions = Completion::select('id', 'barcode_number')->orderBy('barcode_number', 'ASC')->get();
		$issue_locations = DB::table('materials')
		->select('location')
		->orderBy('location', 'asc')
		->groupBy('location')
		->having('location', '<>', '')
		->get();
		$receive_locations = array('SX91', 'CL91', 'FL91', 'SX51', 'CL51', 'FL51', 'CLB9', 'SX21', 'CL21', 'FL21', 'VNA0', 'VN51', 'FLT9', 'CLA0', 'SXA0', 'SXA1', 'SXT9', 'FLA1', 'FLA2', 'SXA2', 'CLA2', 'FLA0', 'VN91', 'VN11', 'VN21', 'FSTK');
		return View::make('transfers.add', array(
			'page' => 'transfers',
			'completions' => $completions,
			'issue_locations' => $issue_locations,
			'receive_locations' => $receive_locations
		));
	}

	public function openAddPageWithCompletion($completion_id) {
		$completions = Completion::orderBy('barcode_number', 'ASC')->get();
		return View::make('transfers.add', array(
			'page' => 'transfers',
			'completion_id' => $completion_id,
			'completions' => $completions
		));
	}

	/**
	 * Save product
	 *
	 * @return
	 */

	public function createTransfer() {

		// Completion
		$data = Input::all();
		$completion = self::getCompletionById($data["completion_id"]);
		$data['material_id'] = $completion->material_id;
		$validator = Validator::make($data, Transfer::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data["user_id"] = Session::get('id');
		$transfer = Transfer::create($data);

		return Redirect::route('listtransfers');
	}

	/**
	 * Display a detail product page
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 */

	public function openDetailPage($id) {
		try {
			$transfer = Transfer::findOrFail($id);
			return View::make('transfers.detail', array(
				'page' => 'transfers',
				'transfer' => $transfer
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
	 */

	public function openEditPage($id) {
		try {
			$transfer = Transfer::findOrFail($id);
			$completions = Completion::select('id', 'barcode_number')->orderBy('barcode_number', 'ASC')->get();
			$issue_locations = DB::table('materials')
			->select('location')
			->orderBy('location', 'asc')
			->groupBy('location')
			->having('location', '<>', '')
			->get();
			$receive_locations = array('SX91', 'CL91', 'FL91', 'SX51', 'CL51', 'FL51', 'CLB9', 'SX21', 'CL21', 'FL21', 'VNA0', 'VN51', 'FLT9', 'CLA0', 'SXA0', 'SXA1', 'SXT9', 'FLA1', 'FLA2', 'SXA2', 'CLA2', 'FLA0', 'VN91', 'VN11', 'VN21', 'FSTK');
			return View::make('transfers.edit', array(
				'page' => 'transfers',
				'transfer' => $transfer,
				'completions' => $completions,
				'issue_locations' => $issue_locations,
				'receive_locations' => $receive_locations
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

	public function updateTransfer($id) {

		try {
			// Product
			$transfer = Transfer::findOrFail($id);
			$data = Input::all();
			$completion = self::getCompletionById($data["completion_id"]);
			$data['material_id'] = $completion->material_id;
			$validator = Validator::make($data, Transfer::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$data["user_id"] = Session::get('id');
			$transfer->update($data);
			return Redirect::route('listtransfers');
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
	 * @return
	 */

	public function deleteTransfer($id) {

		try {
			// Product
			$transfer = Transfer::findOrFail($id);
			$transfer->delete();
			return Redirect::route('listtransfers');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Open Scan Transfer page
	 *
	 * @return View
	 *
	 */

	public function openTransferPage() {
		return View::make('transfers.scan');
	}

	/**
	 * AJAX Transfer
	 *
	 * @return JSON 	response (status, status code, message)
	 *
	 */

	public function transferItem() {

		$data = Input::all();
		$transfer = self::getTransfer($data["barcode_number"]);

		if ($transfer == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar."
			);
			return Response::json($response);
		}

		$reference_barcode = $transfer->barcode_number;
		$completion = self::getCompletion($reference_barcode);

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

		// $inventory = self::getInventoryLot($transfer->material_number);
		$inventory = self::getInventoryLot($reference_barcode);
		if ($inventory == null) {
			// Barcode belum di completion
			$response = array(
				'status' => false, 
				'status_code' => 1002,
				'message' => "Barcode tidak dapat di transfer karena completion belum dilakukan."
			);
			return Response::json($response);
		}

		// Check barcode lot in inventory

		if ($inventory->lot <= 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Jumlah pada inventory tidak tersedia"
			);
			return Response::json($response);
		}

		if($transfer->issue_location == 'SX51' && $transfer->category == 'KEY' && $completion->limit_used != 1){
			try{
				$tes = DB::connection('mysql2')
				->table('barrel_queues')
				->insert([
					'material_number' => $transfer->material_number,
					'tag' => $completion->barcode_number,
					'quantity' => $completion->lot_completion,
					'created_at' => date( 'Y-m-d H:i:s'),
					'updated_at' => date( 'Y-m-d H:i:s')
				]);
			}
			catch(\Exception $e){
				
			}
		}
		if($transfer->issue_location == 'SX51' && $transfer->category == 'KEY' && $completion->limit_used == 1){
			try{
				$tes = DB::connection('mysql2')
				->table('barrel_queue_inactives')
				->insert([
					'material_number' => $transfer->material_number,
					'tag' => $completion->barcode_number,
					'quantity' => $completion->lot_completion,
					'created_at' => date( 'Y-m-d H:i:s'),
					'updated_at' => date( 'Y-m-d H:i:s')
				]);
			}
			catch(\Exception $e){
				
			}
		}

		// Check barcode lead time

		// if ($inventory->last_action == "transfer" || $inventory->last_action == "adjustment_transfer") {
		// 	$nowTimestamp = self::getTimestamp();
		// 	$completionTimestamp = $completion->lead_time;
		// 	$leadTime = $completionTimestamp * 60;
		// 	$lastScanTimestamp = strtotime($inventory->updated_at);

		// 	if (($lastScanTimestamp + $leadTime) >= $nowTimestamp) {
		// 		$response = array(
		// 			'status' => false, 
		// 			'status_code' => 1002,
		// 			'message' => "Barcode tidak dapat di completion karena masih dalam waktu lead time."
		// 		);
		// 		return Response::json($response);
		// 	}	
		// }

		if ($completion->limit_used == 1) {
			self::deactivedCompletion($completion->id);
		}

		$data["material_number"] = $transfer->material_number;
		$data["barcode_number"] = $reference_barcode;
		$data["lot"] = $inventory->lot - $transfer->lot_transfer;
		$data["last_action"] = "transfer";
		$data["status"] = "1";
		self::updateInventory($inventory->id, $data);

		$history['category'] = "transfer";
		$history['completion_description'] = $transfer->description;
		$history['transfer_barcode_number'] = $transfer->barcode_number_transfer;
		$history['transfer_document_number'] = "8190";
		$history['transfer_material_id'] = $transfer->material_id;
		$history['transfer_issue_location'] = $transfer->issue_location;
		$history['transfer_issue_plant'] = $transfer->issue_plant;
		$history['transfer_receive_location'] = $transfer->receive_location;
		$history['transfer_receive_plant'] = $transfer->receive_plant;
		$history['transfer_transaction_code'] = $transfer->transaction_code;
		$history['transfer_movement_type'] = $transfer->movement_type;
		$history['transfer_reason_code'] = $transfer->reason_code;
		$history['lot'] = $transfer->lot_transfer;
		$history['synced'] = 0;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);

		try{
			$tes = DB::connection('mysql2')
			->table('middle_material_requests')
			->where('middle_material_requests.material_number', '=', $transfer->material_number)
			->update([
				'quantity' => DB::raw('quantity-'.$transfer->lot_transfer.''),
				'updated_at' => date('Y-m-d H:i:s')
			]);
		}
		catch(\Exception $e){
		}

		$response = array(
			'status' => true, 
			'message' => "Transfer berhasil dilakukan",
			'data' => json_encode($history)
		);
		return Response::json($response);

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
		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('transfers-adjustment.add', array(
			'page' => 'transfer_adjustment',
			'materials' => $materials,
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Display a add adjustment transfers excel page
	 *
	 * @return View
	 *
	 */

	public function openAdjustmentExcelPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		return View::make('transfers-adjustment.excel', array(
			'page' => 'transfer_adjustment_excel',
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	public function transferAdjustmentFromExcel() {

		$data = Input::all();
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
				// $response = self::transferAdjustment($barcode, $data['user_id'], $posting_date);
				$response = self::transferAdjustment($barcode, $data['user_id']);

				if ($response['status'] == true) {
					// $adjustment = json_decode($response['data']);
					// $history['category'] = "transfer_adjustment";
					// $history['transfer_barcode_number'] = $adjustment->transfer_barcode_number;
					// $history['transfer_material_id'] = $adjustment->transfer_material_id;
					// $history['transfer_issue_location'] = $adjustment->transfer_issue_location;
					// $history['transfer_issue_plant'] = $adjustment->transfer_issue_plant;
					// $history['transfer_receive_location'] = $adjustment->transfer_receive_location;
					// $history['transfer_receive_plant'] = $adjustment->transfer_receive_plant;
					// $history['transfer_transaction_code'] = $adjustment->transfer_transaction_code;
					// $history['transfer_movement_type'] = $adjustment->transfer_movement_type;
					// $history['transfer_reason_code'] = $adjustment->transfer_reason_code;
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

		return View::make('transfers-adjustment.list', array(
			'page' => 'transfer_adjustment',
			'category' => 'excel',
			'adjustments' => $response
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

	function transferAdjustment($barcode, $user_id) {//, $posting_date) {

		$data['user_id'] = $user_id;
		$data['barcode_number'] = $barcode;
		$transfer = self::getTransfer($data["barcode_number"]);

		// Check barcode is registered

		if ($transfer == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar.",
				'barcode' => $barcode
			);
			return $response;
		}

		$reference_barcode = $transfer->barcode_number;
		$completion = self::getCompletion($reference_barcode);

		// Check barcode is active

		if ($completion->active == 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1001,
				'message' => "Barcode tidak aktif",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode on inventory

		// $inventory = self::getInventoryLot($transfer->material_number);
		$inventory = self::getInventoryLot($reference_barcode);
		if ($inventory == null) {
			// Barcode belum di completion
			$response = array(
				'status' => false, 
				'status_code' => 1002,
				'message' => "Barcode tidak dapat di transfer karena completion belum dilakukan.",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode lot in inventory

		if ($inventory->lot <= 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Jumlah pada inventory tidak tersedia",
				'barcode' => $barcode
			);
			return $response;
		}

		// Check barcode lead time

		// if ($inventory->last_action == "transfer" || $inventory->last_action == "adjustment_transfer") {
		// 	$nowTimestamp = self::getTimestamp();
		// 	$completionTimestamp = $completion->lead_time;
		// 	$leadTime = $completionTimestamp * 60;
		// 	$lastScanTimestamp = strtotime($inventory->updated_at);

		// 	if (($lastScanTimestamp + $leadTime) >= $nowTimestamp) {
		// 		$response = array(
		// 			'status' => false, 
		// 			'status_code' => 1002,
		// 			'message' => "Barcode tidak dapat di completion karena masih dalam waktu lead time."
		// 		);
		// 		return $response;
		// 	}	
		// }
		
		if ($completion->limit_used == 1) {
			self::deactivedCompletion($completion->id);
		}

		$data["material_number"] = $transfer->material_number;
		$data["barcode_number"] = $reference_barcode;
		$data["lot"] = $inventory->lot - $transfer->lot_transfer;
		$data["last_action"] = "transfer_adjustment";
		$data["status"] = "1";
		self::updateInventory($inventory->id, $data);

		$history['category'] = "transfer_adjustment";
		$history['transfer_barcode_number'] = $transfer->barcode_number_transfer;
		$history['completion_description'] = $transfer->description;
		$history['transfer_material_id'] = $transfer->material_id;
		$history['transfer_issue_location'] = $transfer->issue_location;
		$history['transfer_issue_plant'] = $transfer->issue_plant;
		$history['transfer_receive_location'] = $transfer->receive_location;
		$history['transfer_receive_plant'] = $transfer->receive_plant;
		$history['transfer_transaction_code'] = $transfer->transaction_code;
		$history['transfer_movement_type'] = $transfer->movement_type;
		$history['transfer_reason_code'] = $transfer->reason_code;
		$history['lot'] = $transfer->lot_transfer;
		$history['synced'] = 0;
		// $history['created_at'] = $posting_date;
		// $history['updated_at'] = $posting_date;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);

		if($transfer->issue_location == 'SX51' && $transfer->category == 'KEY' && $completion->limit_used != 1){
			try{
				$tes = DB::connection('mysql2')
				->table('barrel_queues')
				->insert([
					'material_number' => $transfer->material_number,
					'tag' => $completion->barcode_number,
					'quantity' => $completion->lot_completion,
					'created_at' => date( 'Y-m-d H:i:s'),
					'updated_at' => date( 'Y-m-d H:i:s')
				]);
			}
			catch(\Exception $e){

			}
		}
		
		if($transfer->issue_location == 'SX51' && $transfer->category == 'KEY' && $completion->limit_used == 1){
			try{
				$tes = DB::connection('mysql2')
				->table('barrel_queue_inactives')
				->insert([
					'material_number' => $transfer->material_number,
					'tag' => $completion->barcode_number,
					'quantity' => $completion->lot_completion,
					'created_at' => date( 'Y-m-d H:i:s'),
					'updated_at' => date( 'Y-m-d H:i:s')
				]);
			}
			catch(\Exception $e){
				
			}
		}

		try{
			$tes = DB::connection('mysql2')
			->table('middle_material_requests')
			->where('middle_material_requests.material_number', '=', $transfer->material_number)
			->update([
				'quantity' => DB::raw('quantity-'.$transfer->lot_transfer.''),
				'updated_at' => date('Y-m-d H:i:s')
			]);
		}
		catch(\Exception $e){	
		}

		$response = array(
			'status' => true, 
			'message' => "Transfer berhasil dilakukan",
			'data' => json_encode($history),
			'barcode' => $barcode
		);
		return $response;
	}

	/**
	 * Display a add adjustment transfers manual page
	 *
	 * @return View
	 *
	 */

	public function openAdjustmentManualPage() {
		$user_id = Session::get('id');
		$level_id = Session::get('level_id');
		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('transfers-adjustment.manual', array(
			'page' => 'transfer_adjustment_manual',
			'materials' => $materials,
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * Adjustment Transfer and display success and failed page
	 * Adjustment Manual
	 *
	 * @return View
	 *
	 */

	public function transferAdjustmentManual() {

		$data = Input::all();
		$date = $data['date'];
		$time = $data['time'];
		$datetime = $date . " " . $time;
		$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
		$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
		$data["created_at"] = $mysqlDate;
		$validator = Validator::make($data, TransferAdjustment::$rules);
		
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data["user_id"] = Session::get('id');
		$data["active"] = 1;
		unset($data['date']);
		unset($data['time']);
		unset($data['hour']);
		unset($data['minute']);

		$transfer = TransferAdjustment::create($data);

		if ($transfer) {
			$history['category'] = "transfer_adjustment";
			$history['transfer_document_number'] = $transfer->document_number;
			$history['transfer_material_id'] = $transfer->material_id;
			$history['transfer_issue_location'] = $transfer->issue_location;
			$history['transfer_issue_plant'] = $transfer->issue_plant;
			$history['transfer_receive_plant'] = $transfer->receive_plant;
			$history['transfer_receive_location'] = $transfer->receive_location;
			$history['transfer_cost_center'] = $transfer->cost_center;
			$history['transfer_gl_account'] = $transfer->gl_account;
			$history['transfer_transaction_code'] = $transfer->transaction_code;
			$history['transfer_movement_type'] = $transfer->movement_type;
			$history['transfer_reason_code'] = $transfer->reason_code;
			$history['lot'] = $transfer->lot;
			$history['synced'] = 0;
			$history['created_at'] = $mysqlDate;
			if (isset($data['user_id'])) {
				$history['user_id'] = $data['user_id'];
			}
			History::create($history);
		}

		return View::make('completions-adjustment.list', array(
			'page' => 'transfer_adjustment',
			'category' => 'manual',
			'adjustments' => $transfer
		));

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
		return View::make('transfers-cancel.add', array(
			'page' => 'transfer_cancel',
			'level_id' => $level_id,
			'user_id' => $user_id
		));
	}

	/**
	 * AJAX Transfer
	 *
	 * @return JSON 	response (status, status code, message)
	 *
	 */

	public function cancelTransferItem() {

		$data = Input::all();
		$transfer = self::getTransfer($data["barcode_number"]);

		// Check barcode is registered

		if ($transfer == null) {
			$response = array(
				'status' => false,
				'status_code' => 1000,
				'message' => "Barcode tidak terdaftar."
			);
			return Response::json($response);
		}

		$reference_barcode = $transfer->barcode_number;
		$completion = self::getCompletion($reference_barcode);

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

		// $inventory = self::getInventoryLot($transfer->material_number);
		$inventory = self::getInventoryLot($reference_barcode);
		if ($inventory == null) {
			// Barcode belum di completion
			$response = array(
				'status' => false, 
				'status_code' => 1002,
				'message' => "Barcode tidak dapat di cancel transfer karena transfer belum dilakukan."
			);
			return Response::json($response);
		}

		$lastTransfer = self::getLastTransferHistory($data["barcode_number"]);
		if (count($lastTransfer) == 0) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Barcode tidak dapat di cancel transfer karena transfer belum dilakukan."
			);
			return Response::json($response);
		}

		if ($inventory->lot >= $completion->lot_completion) {
			$response = array(
				'status' => false, 
				'status_code' => 1003,
				'message' => "Transfer belum dilakukan"
			);
			return Response::json($response);
		}

		// Check barcode lot in inventory

		// if ($inventory->lot <= 0) {
		// 	$response = array(
		// 		'status' => false, 
		// 		'status_code' => 1003,
		// 		'message' => "Jumlah pada inventory tidak tersedia"
		// 	);
		// 	return Response::json($response);
		// }

		// Check barcode lead time

		// if ($inventory->last_action == "transfer" || $inventory->last_action == "adjustment_transfer") {
		// 	$nowTimestamp = self::getTimestamp();
		// 	$completionTimestamp = $completion->lead_time;
		// 	$leadTime = $completionTimestamp * 60;
		// 	$lastScanTimestamp = strtotime($inventory->updated_at);

		// 	if (($lastScanTimestamp + $leadTime) >= $nowTimestamp) {
		// 		$response = array(
		// 			'status' => false, 
		// 			'status_code' => 1002,
		// 			'message' => "Barcode tidak dapat di completion karena masih dalam waktu lead time."
		// 		);
		// 		return Response::json($response);
		// 	}	
		// }

		$data["material_number"] = $transfer->material_number;
		$data["barcode_number"] = $reference_barcode;
		$data["lot"] = $inventory->lot + $transfer->lot_transfer;
		$data["last_action"] = "transfer_cancel";
		$data["status"] = "0";
		self::updateInventory($inventory->id, $data);

		$history['category'] = "transfer_cancel";
		$history['transfer_barcode_number'] = $transfer->barcode_number_transfer;
		$history['completion_description'] = $transfer->description;
		$history['transfer_material_id'] = $transfer->material_id;
		$history['transfer_issue_location'] = $transfer->issue_location;
		$history['transfer_issue_plant'] = $transfer->issue_plant;
		$history['transfer_receive_location'] = $transfer->receive_location;
		$history['transfer_receive_plant'] = $transfer->receive_plant;
		$history['transfer_transaction_code'] = $transfer->transaction_code;

		// todo
		// menentukan movement type ini
		// jika transfer history terakhir dengan reference barcode ini belum diupload dan tidak ada reference filenamenya
		// maka movement typenya 9i3
		// jika transfer history terakhir dengan reference barcode ini sudah diupload dan ada reference filenamenya
		// maka movement typenya 9i4

		// if ($transfer->movement_type == "9i3") {
		// 	$history['transfer_movement_type'] = "9i4";
		// }
		// else if ($transfer->movement_type == "9p1") {
		// 	$history['transfer_movement_type'] = "9p2";
		// }
		// else {
		// 	$history['transfer_movement_type'] = "9i4";
		// }

		// if ($lastTransfer->synced == 0 && $lastTransfer->reference_file == null) {
		// 	if (strtoupper($transfer->movement_type) == "9p1") {
		// 		$history['transfer_movement_type'] = "9P1";
		// 	}
		// 	else {
		// 		$history['transfer_movement_type'] = "9I3";
		// 	}
		// }
		// else {
		if (strtoupper($transfer->movement_type) == "9P1") {
			$history['transfer_movement_type'] = "9P2";
		}
		else {
			$history['transfer_movement_type'] = "9I4";
		}
		// }

		$history['transfer_reason_code'] = $transfer->reason_code;
		$history['lot'] = $transfer->lot_transfer;
		$history['synced'] = 0;
		if (isset($data['user_id'])) {
			$history['user_id'] = $data['user_id'];
		}
		History::create($history);

		if($transfer->issue_location == 'SX51' && $transfer->category == 'KEY'){
			try{
				$tes = DB::connection('mysql2')
				->table('barrel_queues')
				->where('tag', '=', $transfer->barcode_number_transfer)
				->delete();
			}
			catch(\Exception $e){

			}
		}

		try{
			$tes = DB::connection('mysql2')
			->table('middle_material_requests')
			->where('middle_material_requests.material_number', '=', $transfer->material_number)
			->update([
				'quantity' => DB::raw('quantity+'.$transfer->lot_transfer.''),
				'updated_at' => date('Y-m-d H:i:s')
			]);
		}
		catch(\Exception $e){	
		}

		$response = array(
			'status' => true,
			'message' => "Cancel Transfer berhasil dilakukan",
			'data' => json_encode($history)
		);
		return Response::json($response);

	}

	public function openHistoryPage() {

		$materials = Material::orderBy('material_number', 'ASC')->get();
		return View::make('transfers.history-filter', array(
			'page' => 'transfer_history',
			'materials' => $materials
		));
	}

	public function filterTransferHistory() {

		$data = Input::all();
		$historiesTable = DB::table('histories')
		->join('materials', 'histories.transfer_material_id', '=', 'materials.id')
		->leftJoin('users', 'histories.user_id', '=', 'users.id')
		->select(
			'histories.id',
			DB::raw('(CASE histories.category
				WHEN "transfer" THEN "Transfer"
				WHEN "transfer_adjustment" THEN "Transfer Adjustment"
				WHEN "transfer_adjustment_excel" THEN "Transfer Excel"
				WHEN "transfer_adjustment_manual" THEN "Transfer Manual"
				WHEN "transfer_cancel" THEN "Transfer Cancel"
				WHEN "transfer_return" THEN "Transfer Return"
				WHEN "transfer_repair" THEN "Transfer Repair"
				WHEN "transfer_after_repair" THEN "Transfer After Repair"
				WHEN "transfer_error" THEN "Transfer Error"
				ELSE "Unidentified" END) AS category'),
			'histories.transfer_barcode_number',
			'histories.transfer_document_number',
			'histories.transfer_material_id',
			'histories.transfer_issue_location',
			'histories.transfer_issue_plant',
			'histories.transfer_receive_location',
			'histories.transfer_receive_plant',
			'histories.transfer_cost_center',
			'histories.transfer_gl_account',
			'histories.transfer_transaction_code',
			'histories.transfer_movement_type',
			'histories.transfer_reason_code',
			'histories.lot',
			'materials.material_number',
			'materials.description',
			'users.name',
			'histories.created_at',
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
			$historiesTable = $historiesTable->whereIn('histories.transfer_barcode_number', $barcodes);
		}
		if (isset($data['material_number']) && strlen($data['material_number']) > 0) {
			$materials = explode(",", $data['material_number']);
			$materialsIDsz = DB::table('materials')->select('id')->whereIn('material_number', $materials)->get();
			$materialsIDs = Array();
			foreach ($materialsIDsz as $object) {
				array_push($materialsIDs, $object->id);
			}
			$historiesTable = $historiesTable->whereIn('histories.transfer_material_id', $materialsIDs);
		}
		if (isset($data['issue_location']) && strlen($data['issue_location']) > 0) {
			$locations = explode(",", $data['issue_location']);
			$historiesTable = $historiesTable->whereIn('histories.transfer_issue_location', $locations);
		}
		if (isset($data['issue_plant']) && strlen($data['issue_plant']) > 0) {
			$plants = explode(",", $data['issue_plant']);
			$historiesTable = $historiesTable->whereIn('histories.transfer_issue_plant', $plants);
		}
		if (isset($data['receive_location']) && strlen($data['receive_location']) > 0) {
			$locations = explode(",", $data['receive_location']);
			$historiesTable = $historiesTable->whereIn('histories.transfer_receive_location', $locations);
		}
		if (isset($data['receive_plant']) && strlen($data['receive_plant']) > 0) {
			$plants = explode(",", $data['receive_plant']);
			$historiesTable = $historiesTable->whereIn('histories.transfer_receive_plant', $plants);
		}
		if (isset($data['category']) && strlen($data['category']) > 0) {
			$categories = explode(",", $data['category']);
			$historiesTable = $historiesTable->where('histories.category','=', $categories);
		}
		$histories = $historiesTable
		->whereIn('histories.category', array(
			'transfer', 
			'transfer_adjustment',  
			'transfer_adjustment_excel', 
			'transfer_adjustment_manual', 
			'transfer_cancel',
			'transfer_error',
			'transfer_return',
			'transfer_repair',
			'transfer_after_repair'
		))
		->orderBy('histories.created_at', 'asc')
		->get();

		if(isset($data['export'])) {
			$data = array(
				'histories' => $histories
			);
			Excel::create('History Transfers', function($excel) use ($data){
				$excel->sheet('History', function($sheet) use ($data) {
					return $sheet->loadView('transfers.history-excel', $data);
				});
			})->export('xls');
		}
		else {

			return View::make('transfers.history', array(
				'page' => 'transfer_history',
				'histories' => $histories,
				'data' => $data
			));
		}
	}

	/**
	 * Export completions to Excel File
	 *
	 * @return Ms. Excel File
	 *
	 */

	public function exportToExcel() {

		$data = Input::all();
		$transfers;
		if (isset($data) && count($data) > 0) {
			$transfers = self::getFilterTransfers($data);
		}
		else {
			// $transfers = Transfer::with(array('completion'))
			// 				->orderBy('barcode_number_transfer', 'ASC')
			// 				->get();
			$transfers = DB::table('transfers')
			->leftJoin('materials', 'transfers.material_id', '=', 'materials.id')
			->leftJoin('completions', 'transfers.completion_id', '=', 'completions.id')
			->select(
				'transfers.barcode_number_transfer',
				'materials.material_number',
				'materials.description',
				'transfers.issue_location',
				'transfers.issue_plant',
				'transfers.receive_location',
				'transfers.receive_plant',
				'transfers.transaction_code',
				'transfers.movement_type',
				'transfers.reason_code',
				'transfers.lot_transfer',
				'completions.barcode_number'
			)
			->orderBy('transfers.id', 'asc')->get();

		}

		$data = array(
			'transfers' => $transfers
		);
		Excel::create('Master Transfer', function($excel) use ($data){
			$excel->sheet('Master', function($sheet) use ($data) {
				return $sheet->loadView('transfers.excel', $data);
			});
		})->export('xls');
	}

	/**
	 * Export transfer histories to Excel File
	 *
	 * @return Ms. Excel File
	 *
	 */

	public function exportHistoryToExcel() {
		self::filterTransferHistory();
	}

	/**
	 * Import transfer master from Excel File
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
					$completion = Completion::where("barcode_number", "=", $row->completion_barcode_number)->first();
					if ($material != null) {
						$data['barcode_number_transfer'] = $row->barcode_number;
						$data['material_id'] = $material->id;
						$data['issue_location'] = $row->issue_location;
						$data['issue_plant'] = $row->issue_plant;
						$data['receive_location'] = $row->receive_location;
						$data['receive_plant'] = $row->receive_plant;
						$data['transaction_code'] = $row->transaction_code;
						$data['movement_type'] = $row->movement_type;
						$data['reason_code'] = $row->reason_code;
						$data['lot_transfer'] = $row->lot;
						$data['completion_id'] = $completion->id;
						$data["user_id"] = Session::get('id');
						if ($row->barcode_number != null) {
							$existedTransfer = Transfer::where("barcode_number_transfer", "=", $row->barcode_number)->first();
							if ($existedTransfer == null) {
								$transfer = Transfer::create($data);
							}
						}
					}
				});
			});
			return Redirect::route('listtransfers');
		}
	}

	function openTemporaryPage() {

		$temporaries = DB::table('histories')
		->join('materials', 'histories.transfer_material_id', '=', 'materials.id')
		->where('histories.synced', '=', 0)
		->whereIn('histories.category', array('transfer', 'transfer_adjustment', 'transfer_adjustment_excel', 'transfer_adjustment_manual', 'transfer_cancel', 'transfer_error', 'transfer_return', 'transfer_repair', 'transfer_after_repair'))
		->select(
			DB::raw('(CASE histories.category
				WHEN "transfer" THEN "Transfer"
				WHEN "transfer_adjustment" THEN "Transfer Adjustment"
				WHEN "transfer_adjustment_excel" THEN "Transfer Excel"
				WHEN "transfer_adjustment_manual" THEN "Transfer Manual"
				WHEN "transfer_cancel" THEN "Transfer Cancel"
				WHEN "transfer_return" THEN "Transfer Return"
				WHEN "transfer_repair" THEN "Transfer Repair"
				WHEN "transfer_after_repair" THEN "Transfer After Repair"
				WHEN "transfer_error" THEN "Transfer Error"
				ELSE "Unidentified" END) AS category'),
			'histories.transfer_barcode_number', 
			'histories.transfer_document_number', 
			'histories.transfer_issue_location', 
			'histories.transfer_issue_plant', 
			'histories.transfer_receive_location', 
			'histories.transfer_receive_plant', 
			'histories.transfer_material_id',
			'histories.transfer_cost_center', 
			'histories.transfer_gl_account',
			'histories.transfer_transaction_code',
			'histories.transfer_movement_type',
			'histories.transfer_reason_code',
			'materials.material_number',
			DB::raw('SUM(histories.lot) as lot'), 
			'histories.synced', 
			'histories.user_id', 
			'histories.deleted_at', 
			'histories.created_at', 
			'histories.updated_at',
			'histories.reference_file',
			'histories.error_description'
		)
		->groupBy(
			'histories.transfer_material_id',
			'histories.transfer_issue_location',
			'histories.transfer_issue_plant',
			'histories.transfer_receive_location',
			'histories.transfer_receive_plant',
			'histories.transfer_transaction_code',
			'histories.transfer_movement_type'
		)
		->having(DB::raw('SUM(histories.lot)'), '<', 1)
		->get();

		return View::make('transfers.temporary', array(
			'page' => 'transfer_temporaries',
			'temporaries' => $temporaries
		));

	}

	/**
	 * Delete Transfer Temporary by Material ID
	 *
	 * @param id 		material id
	 *
	 */

	function deleteTransferTemporary($id) {

		DB::table('histories')
		->where('transfer_material_id', '=', $id)
		->where('histories.synced', '=', 0)
		->whereIn('histories.category', array(
			'transfer', 
			'transfer_adjustment', 
			'transfer_adjustment_excel', 
			'transfer_adjustment_manual', 
			'transfer_cancel', 
			'transfer_return', 
			'transfer_repair',
			'transfer_after_repair',
			'transfer_error'
		))
		->delete();
		return Redirect::route('temporaryTransfers');
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
			'materials.lead_time'
		)
		->where('completions.barcode_number', $barcode)
		->first();
		return $completions;
	}

	/**
	 * Get Completion by Completion ID
	 *
	 * @param id 		completion id
	 *
	 * @return Completion
	 *
	 */

	function getCompletionById($id) {
		try {
			$completion = Completion::findOrFail($id);
			return $completion;
		}
		catch(ModelNotFoundException $e) {
			return null;
		}
		// $completions = Completion::find;
		// return $completions;
	}

	/**
	 * Get Last Transfer History by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return History
	 *
	 */

	function getLastTransferHistory($barcode) {
		$histories = DB::table('histories')
		->where('transfer_barcode_number', $barcode)
		->whereIn('category', array("transfer", "transfer_adjustment"))
		->orderBy('created_at', 'DESC')
		->first();
		return $histories;
	}

	/**
	 * Get Completion by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Completion
	 *
	 */

	function getTransfer($barcode) {
		$transfers = DB::table('transfers')
		->leftJoin('materials', 'transfers.material_id', '=', 'materials.id')
		->leftJoin('completions', 'transfers.completion_id', '=', 'completions.id')
		->select(
			'transfers.id', 
			'transfers.barcode_number_transfer', 
			'transfers.material_id', 
			'transfers.issue_location', 
			'transfers.issue_plant', 
			'transfers.receive_location', 
			'transfers.receive_plant', 
			'transfers.transaction_code', 
			'transfers.movement_type', 
			'transfers.reason_code', 
			'transfers.lot_transfer',
			'completions.barcode_number',
			'materials.material_number',
			'materials.lead_time',
			'materials.category',
			'materials.description'
		)
		->where('barcode_number_transfer', $barcode)->first();
		return $transfers;
	}

	/**
	 * Get Inventory Lot by Material Number
	 *
	 * @param material 		material number
	 *
	 * @return Inventory
	 *
	 */

	// function getInventoryLot($material_number) {
	// 	$inventories = DB::table('inventories')->where('material_number', $material_number)->first();
	// 	return $inventories;
	// }

	/**
	 * Get Inventory Lot by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Inventory
	 *
	 */

	function getInventoryLot($barcode_number) {
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

}