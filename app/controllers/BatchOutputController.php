<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class BatchOutputController extends BaseController {

	/**
	 * Display a list batch outputs page
	 *
	 * @return View
	 */
	
	public function openListPage() {

		$batchoutputs = DB::table('histories')
					->where('histories.synced', '=', 1)
					->select(
						'histories.category',
						'histories.reference_file',
						'histories.synced',
						'histories.updated_at'
					)
					->orderBy('histories.updated_at', 'desc')
					->groupBy(
						'histories.reference_file'
    				)
                    ->get();
		return View::make('batchoutputs.list', array(
			'page' => 'batchoutputs',
			'batchoutputs' => $batchoutputs
		));
	}

	/**
	 * Display a add batch outputs page
	 *
	 * @return View
	 */

	public function openAddPage() {
		return View::make('batchoutputs.add', array(
			'page' => 'batchoutputs'
		));
	}

	/**
	 * Save category
	 *
	 * @return
	 */

	public function createBatchOutput() {

		// Category
		
		$data = Input::all();
		unset($data['hour']);
		unset($data['minute']);
		$data['active'] = 1;
		$validator = Validator::make($data, BatchOutput::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$batchOutput = BatchOutput::create($data);

		return Redirect::route('listbatchoutputs');
	}

	/**
	 * Display a detail category page
	 * 
	 * @param   $id identity of category
	 *
	 * @return View
	 */

	public function openDetailPage($reference_file) {
		try {
			// $batchOutput = BatchOutput::findOrFail($id);
			if ($reference_file)
			if (strpos($reference_file, 'ympigm') !== false) {
				$batchoutputs = DB::table('histories')
							->join('materials', 'histories.transfer_material_id', '=', 'materials.id')
							->where('histories.synced', '=', 1)
							->where('histories.reference_file', '=', $reference_file)
							->select(
								'histories.category',
								'histories.transfer_barcode_number', 
								'histories.transfer_document_number', 
								'histories.transfer_issue_location', 
								'histories.transfer_issue_plant', 
								'histories.transfer_receive_location', 
								'histories.transfer_receive_plant', 
								'histories.transfer_cost_center',
								'histories.transfer_gl_account',
								'histories.transfer_transaction_code',
								'histories.transfer_movement_type',
								'histories.transfer_reason_code',
								'histories.error_description',
								'materials.material_number',
								'histories.transfer_material_id',
								'histories.lot as lot', 
								'histories.synced', 
								'histories.reference_file',
								'histories.user_id', 
								'histories.deleted_at', 
								'histories.created_at', 
								'histories.updated_at'
							)
							->groupBy(
								'histories.reference_file'
							)
				            ->having(DB::raw('histories.lot'), '>', 0)
				            ->get();

				return View::make('batchoutputs.detail', array(
					'page' => 'batchoutputs',
					'type' => 'transfer',
					'batchoutputs' => $batchoutputs
				));
			}
			else {
				$batchoutputs = DB::table('histories')
							->join('materials', 'histories.completion_material_id', '=', 'materials.id')
							->where('histories.synced', '=', 1)
							->where('histories.reference_file', '=', $reference_file)
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
								'histories.synced', 
								'histories.reference_file',
								'histories.user_id', 
								'histories.deleted_at', 
								'histories.created_at', 
								'histories.updated_at'
							)
							->groupBy(
								'histories.completion_material_id',
		    					'histories.completion_location',
		    					'histories.completion_issue_plant'
		    				)
		                    ->having(DB::raw('SUM(histories.lot)'), '>', 0)
		                    ->get();

				return View::make('batchoutputs.detail', array(
					'page' => 'batchoutputs',
					'type' => 'completion',
					'batchoutputs' => $batchoutputs
				));

			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit category page
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function openEditPage($id) {
		try {
			$batchOutput = BatchOutput::findOrFail($id);
			return View::make('batchoutputs.edit', array(
				'page' => 'batchoutputs',
				'batchOutput' => $batchOutput
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

	public function updateBatchOutput($id) {

		try {
			$batchOutput = BatchOutput::findOrFail($id);
			$data = Input::all();
			unset($data['hour']);
			unset($data['minute']);
			$validator = Validator::make($data, BatchOutput::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$batchOutput->update($data);

			return Redirect::route('listbatchoutputs');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete category
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function deleteBatchOutput($id) {

		try {
			// Category
			$batchOutput = BatchOutput::findOrFail($id);
			$batchOutput->delete();

			return Redirect::route('listbatchoutputs');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	public function timeFormat($date) {
		return date_format($date, "H:i");
	}

	public function resumeCompletion($date) {
	// function resumeCompletion() {
		$identifier = strtotime("now");

		$year = date("Y");
		$month = date("m");
		$date2 = date("d");
		$hour = date("H");
		$minute = date("i");
		$second = date("s");
		$identifier = $hour . $minute;
		$filename = "ympi_upload_" . $year . $month . $date2 . $hour . $minute . $second . ".txt";
		$filepath = public_path() . "/outputs/" . $filename;
		// $filedestination =  "/public_html/uploads/" . $filename; // development
		$filedestination = "ma/ympi/prodordconf/" . $filename; // production
		$filearchive = public_path() . "/archived/" . $filename;

		$query = DB::table('histories')
					->join('materials', 'histories.completion_material_id', '=', 'materials.id')
					->where('histories.synced', '=', 0)
					->where('histories.created_at', '<=', $date)
					->whereNull('histories.deleted_at')
					->whereIn('histories.category', array('completion', 'completion_adjustment', 'completion_adjustment_excel', 'completion_adjustment_manual', 'completion_cancel', 'completion_error', 'completion_return', 'completion_repair', 'completion_after_repair', 'completion_temporary_delete'))
					->select(
						'histories.id',
						'histories.category',
						'histories.completion_barcode_number', 
						'histories.completion_description', 
						'histories.completion_location', 
						'histories.completion_issue_plant', 
						'histories.completion_material_id',
						'histories.completion_reference_number',
						'materials.material_number',
						DB::raw('SUM(histories.lot) as lot'), 
						'histories.synced', 
						'histories.user_id', 
						'histories.deleted_at', 
						'histories.created_at', 
						'histories.updated_at'
					)
					->groupBy(
						'histories.completion_material_id',
    					'histories.completion_issue_plant',
						'histories.completion_location'
    				)
                    ->having(DB::raw('SUM(histories.lot)'), '>', 0)
                    ->get();

        $text = "";
        $material_ids = Array();
        if (count($query) > 0) {
        	$index = 1;
        	foreach ($query as $history) {
        		array_push($material_ids, $history->completion_material_id);
				$text .= self::writeString($history->completion_issue_plant, 4, " ");
				$text .= self::writeString($history->completion_location, 4, " ");
				$text .= self::writeString($history->material_number, 18, " ");
				$text .= self::writeDecimal($history->lot, 14, "0");
				//$text .= self::writeDate($history->created_at, "completion");
				$text .= self::writeDate(date('Y-m-d H:i:s'), "completion");
				$text .= self::writeString($history->completion_reference_number, 16, " ");
				if ($index < count($query)) {
					$text .= "\r\n";
				}
				$index++;
			}
			$text .= "\r\n";
			if (File::exists($filename)) {
				File::delete($filepath);
			}
			File::put($filepath, $text);
			copy($filepath, $filearchive);
			// GET all histories ID and UPDATE synced to true

			$queryIDs = DB::table('histories')
							->select('histories.id')
						->whereIn('histories.completion_material_id', $material_ids)
						->whereIn('histories.category', array('completion', 'completion_adjustment', 'completion_adjustment_excel', 'completion_adjustment_manual', 'completion_cancel', 'completion_error', 'completion_return', 'completion_repair', 'completion_after_repair', 'completion_temporary_delete'))
						->where('histories.synced', '=', 0)
						->where('histories.created_at', '<=', $date)
						->get();

			$historiesIDs = Array();
			if (count($queryIDs) > 0) {
				foreach ($queryIDs as $data) {
					array_push($historiesIDs, $data->id);
				}
			}

			$success = self::uploadFTP($filepath, $filedestination);
			if ($success) {
			    $queryHistoriesIDs = DB::table('histories')
									->whereIn('id', $historiesIDs)
									->update(
										array(
											'synced' => 1,
											// 'updated_at' => $date,
											'reference_file' => $filename
										)
									);
				return true;
			}
			else {
				return false;
			}
        }
        else {
        	return false;
        }
	}

	function uploadFTP($from, $to) {
		$upload = FTP::connection()->uploadFile($from, $to);
		return $upload;
	}

	function downloadFTP($from, $to) {
		$download = FTP::connection()->downloadFile($from, $to);
		return $download;
	}

	public function resumeTransfer($date) {
	// function resumeTransfer() {
		$year = date("Y");
		$month = date("m");
		$date2 = date("d");
		$hour = date("H");
		$minute = date("i");
		$second = date("s");
		$filename = "ympigm_upload_" . $year . $month . $date2 . $hour . $minute . $second . ".txt";
		$filepath = public_path() . "/outputs/" . $filename;
		// $filedestination =  "/public_html/uploads/". $filename; // development
		$filedestination = "ma/ympigm/" . $filename;  // production
		$filearchive = public_path() . "/archived/" . $filename;

		$query = DB::table('histories')
					->join('materials', 'histories.transfer_material_id', '=', 'materials.id')
					->where('histories.synced', '=', 0)
					->where('histories.created_at', '<=', $date)
					->whereNull('histories.deleted_at')
					->whereIn('histories.category', array('transfer', 'transfer_adjustment', 'transfer_adjustment_excel', 'transfer_adjustment_manual', 'transfer_cancel', 'transfer_error', 'transfer_return', 'transfer_repair', 'transfer_after_repair'))
					->select(
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
						// DB::raw('SUM(histories.lot) as lot'),
						'histories.lot', 
						'histories.synced', 
						'histories.user_id', 
						'histories.deleted_at', 
						'histories.created_at', 
						'histories.updated_at'
					)
					// ->groupBy(
					// 	'histories.transfer_material_id',
					// 	'histories.transfer_issue_location',
					// 	'histories.transfer_issue_plant',
					// 	'histories.transfer_receive_location',
					// 	'histories.transfer_receive_plant',
					// 	'histories.transfer_transaction_code',
					// 	'histories.transfer_movement_type'
					// )
					// ->having(DB::raw('SUM(histories.lot)'), '>', 0)
					->orderBy('histories.transfer_movement_type', 'desc')
                    ->get();
        
        $text = "";
        $material_ids = Array();
        
        if (count($query) > 0) {
        	$index = 1;
        	foreach ($query as $history) {
        		array_push($material_ids, $history->transfer_material_id);
				// $text .= self::writeString($history->transfer_document_number, 15, " ");
				// $text .= self::writeString("8190", 18, " ");	
				$text .= self::writeString("8190", 15, " ");
				$text .= self::writeString($history->transfer_issue_plant, 4, " ");
				$text .= self::writeString($history->material_number, 18, " ");
				$text .= self::writeString($history->transfer_issue_location, 4, " ");
				$text .= self::writeString($history->transfer_receive_plant, 4, " ");
				$text .= self::writeString($history->transfer_receive_location, 4, " ");
				$text .= self::writeDecimal($history->lot, 13, "0");
				$text .= self::writeString($history->transfer_cost_center, 10, " ");
				$text .= self::writeString($history->transfer_gl_account, 10, " ");
				$text .= self::writeDate($history->created_at, "transfer");
				// $text .= self::writeDate(date('Y-m-d H:i:s'), "transfer");
				$text .= self::writeString($history->transfer_transaction_code, 20, " ");
				$text .= self::writeString($history->transfer_movement_type, 3, " ");
				$text .= self::writeString($history->transfer_reason_code, 4, " ");
				if ($index < count($query)) {
					$text .= "\r\n";
				}
				$index++;
			}
			$text .= "\r\n";
			if (File::exists($filepath)) {
				File::delete($filepath);
			}
			File::put($filepath, $text);
			copy($filepath, $filearchive);

			// GET all histories ID and UPDATE synced to true

			$queryIDs = DB::table('histories')
							->select('histories.id')//, 'histories.category', 'histories.synced')
						->whereIn('histories.transfer_material_id', $material_ids)
						->whereIn('histories.category', array('transfer', 'transfer_adjustment', 'transfer_adjustment_excel', 'transfer_adjustment_manual', 'transfer_cancel', 'transfer_error', 'transfer_return', 'transfer_repair', 'transfer_after_repair'))
						->where('histories.synced', '=', 0)
						->where('histories.created_at', '<=', $date)
						->get();

			$historiesIDs = Array();
			if (count($queryIDs) > 0) {
				foreach ($queryIDs as $data) {
					array_push($historiesIDs, $data->id);
				}
			}

			$success = self::uploadFTP($filepath, $filedestination);
			if ($success) {
				$queryHistoriesIDs = DB::table('histories')
								->whereIn('id', $historiesIDs)
								->update(
									array(
										'synced' => 1,
										// 'updated_at' => $date,
										'reference_file' => $filename
									)
								);
				return true;
			}
			else {
				return false;
			}
        }
        else {
        	return false;
        }
	}

	public function resumeHistories() {
		try {
			$setting = Setting::findOrFail(1);
			if($setting->upload_resume == 1) {
				$date =  date('Y-m-d H:i:s');
				$resumeCompletion = self::resumeCompletion($date);
				$resumeTransfer = self::resumeTransfer($date);
				return ($resumeCompletion && $resumeTransfer)? 'true' : 'false';		
			}
			else {
				return 'false';
			}
		}
		catch(ModelNotFoundException $e) {
			return 'false';
		}
	}

	public function downloadErrorReports() {

		$filepath = "/public_html/downloads/";

		$errorCompletionPath = $filepath . "ympi_error_20160701012715.txt";
		$downloadCompletionDestination = public_path() . "/error_reports/ympi_error_20160701012715.txt";
		$downloadCompletionSuccess = self::downloadFTP($errorCompletionPath, $downloadCompletionDestination);

		$errorTransferPath = $filepath . "ympigm_error_20160701013841.txt";
		$downloadTransferDestination = public_path() . "/error_reports/ympigm_error_20160701013841.txt";
		$downloadTransferSuccess = self::downloadFTP($errorTransferPath, $downloadTransferDestination);

		return ($downloadCompletionSuccess && $downloadTransferSuccess)? 'true' : 'false';
	}

	function writeString($text, $maxLength, $char) {
		if ($maxLength > 0) {
			$textLength = 0;
			if ($text != null) {
				$textLength = strlen($text);
			}
			else {
				$text = "";
			}
			for ($i = 0; $i < ($maxLength - $textLength); $i++) {
				$text .= $char;
			}
		}
		return $text;
	}

	function writeDecimal($text, $maxLength, $char) {
		if ($maxLength > 0) {
			$textLength = 0;
			if ($text != null) {
				$textLength = strlen($text);
			}
			else {
				$text = "";
			}
			for ($i = 0; $i < (($maxLength - 4) - $textLength); $i++) {
				$text = $char . $text;
			}
		}
		$text .= ".000";
		return $text;
	}

	function writeDate($created_at, $type) {
		$datetime = strtotime($created_at);
		if ($type == "completion") {
			$text = date("dmY", $datetime);
			return $text;
		}
		else {
			$text = date("Ymd", $datetime);
			return $text;
		}
	}

}