<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ErrorReportController extends BaseController {

	/**
	 * Display a list batch outputs page
	 *
	 * @return View
	 */
	
	public function openListPage() {

		$histories = DB::table('histories')
		->where('histories.synced', '=', 0)
		->whereNotNull('histories.reference_file')
		->whereNotNull('histories.error_description')
		->whereNull('histories.deleted_at')
		->select(
			'histories.category',
			'histories.reference_file',
			'histories.synced',
			'histories.created_at'
		)
		->orderBy('histories.created_at', 'desc')
		->groupBy(
			'histories.reference_file'
		)
		->get();
		return View::make('error-report.list', array(
			'page' => 'error_report',
			'histories' => $histories
		));

	}

	public function openDetailPage($filename) {
		if (strpos($filename, 'gm') !== false) {
		    // transfer
			$histories = DB::table('histories')
			->where('histories.reference_file', '=', $filename)
			->where('histories.lot', '>', 0)
			->where('histories.synced', '=', 0)
			->whereNull('histories.deleted_at')
			->leftJoin('materials', 'histories.transfer_material_id', '=', 'materials.id')
			->select(
				'histories.id',
				'histories.category',
				'histories.transfer_barcode_number',
				'histories.transfer_document_number',
				'materials.material_number',
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
				'histories.reference_file',
				'histories.lot',
				'histories.synced',
				'histories.error_description',
				'histories.created_at'
			)
			->orderBy('histories.updated_at', 'desc')
			->get();
			return View::make('error-report.detail', array(
				'page' => 'error_report',
				'filename' => $filename,
				'category' => 'transfer_error',
				'histories' => $histories
			));
		}
		else {
			// completion
			$histories = DB::table('histories')
			->where('histories.reference_file', '=', $filename)
			->whereNull('histories.deleted_at')
			->leftJoin('materials', 'histories.completion_material_id', '=', 'materials.id')
			->select(
				'histories.id',
				'histories.category',
				'histories.completion_barcode_number',
				'histories.completion_description',
				'histories.completion_location',
				'histories.completion_issue_plant',
				'materials.material_number',
				'histories.completion_reference_number',
				'histories.reference_file',
				'histories.lot',
				'histories.synced',
				'histories.error_description',
				'histories.created_at'
			)
			->orderBy('histories.updated_at', 'desc')
			->get();
			return View::make('error-report.detail', array(
				'page' => 'error_report',
				'filename' => $filename,
				'category' => 'completion_error',
				'histories' => $histories
			));
		}
	}

	public function openEditPage($filename, $id) {
		try {
			$errorReport = History::findOrFail($id);
			$materials = Material::orderBy('material_number', 'ASC')->get();
			// echo json_encode($errorReport);
			// exit();
			return View::make('error-report.edit', array(
				'materials' => $materials,
				'filename' => $filename,
				'page' => 'completion_error',
				'errorReport' => $errorReport
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	public function updateErrorReport($filename, $id) {
		try {
			$history = History::findOrFail($id);
			$data = Input::all();
			$data["user_id"] = Session::get('id');
			$history->update($data);
			return Redirect::to('/errorreport/' . $filename);
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	public function deleteErrorReport($filename, $id) {
		try {
			// Product
			$history = History::findOrFail($id);
			$history->delete();
			return Redirect::to('/errorreport/' . $filename);
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	function downloadFTP($from, $to) {
		$download = FTP::connection()->downloadFile($from, $to);
		return $download;
	}

	function deleteFTP($path) {
		$delete = FTP::connection()->delete($path);
		return $delete;
	}

	public function importErrorReports() {

		// $completionDirectory = "public_html/downloads";
		// $transferDirectory = "public_html/downloads";

		$completionDirectory = "ma/ympi/prodordconf";
		$transferDirectory = "ma/ympigm";
		$archiveDirectory = public_path() . "/archived";

		try {
			$setting = Setting::findOrFail(1);
			if($setting->download_report == 1) {
				$year = date("Y");
				$month = date("m");
				$date = date("d");
				$hour = date("H");
				$minute = date("i");
				$second = date("s");
				$identifier = $hour . $minute;

				/* Download Completion */

				$listing = FTP::connection()->getDirListing($completionDirectory);
				$dirs = array();
				$successes = array();
				if (count($listing) > 0) {
					foreach ($listing as $key => $value) {
						if (substr_count($value, 'ympi_error_' . $year . $month . $date) > 0) {
							array_push($dirs, $value);
						}
					}
					foreach ($dirs as $dir) {
						$names = explode("/", $dir);
						$filename = $names[count($names) - 1];
						$filepath = $completionDirectory . "/" . $filename;
						$filedestination = "./error_reports/" . $filename;
						$fileArchive = $archiveDirectory . "/" . $filename;
						$success = self::downloadFTP($filepath, $filedestination);
						copy($filedestination, $fileArchive);
						array_push($successes, $success);
						$delete = self::deleteFTP($filepath);
					}
				}

				/* Download Transfer */

				$listing2 = FTP::connection()->getDirListing($transferDirectory);
				$dirs2 = array();
				$successes2 = array();
				if (count($listing2) > 0) {
					foreach ($listing2 as $key => $value) {
						if (substr_count($value, 'ympigm_error_' . $year . $month . $date) > 0) { 
							array_push($dirs2, $value);
						}
					}
					foreach ($dirs2 as $dir2) {
						$names2 = explode("/", $dir2);
						$filename2 = $names2[count($names2) - 1];
						$filepath2 = $transferDirectory . "/" . $filename2;
						$filedestination2 = "./error_reports/" . $filename2;
						$fileArchive = $archiveDirectory . "/" . $filename2;
						$success2 = self::downloadFTP($filepath2, $filedestination2);
						copy($filedestination2, $fileArchive);
						array_push($successes2, $success2);
						$delete2 = self::deleteFTP($filepath2);
					}
				}

				$files = File::files(public_path() . "/error_reports");
				if (count($files) > 0) {
					$filenames = array();
					foreach ($files as $file) {
						$filename = array();
						// Get filename
						$names = explode("/", $file);
						$index = count($names) - 1;
						$name = $names[$index];
						$filename["name"] = $name;

						$text = explode("_", $name);
						if (count($text) > 0) {
							// Get category
							if ($text[0] == "ympi") {
								$filename["category"] = "completion_error";
							}
							else {
								$filename["category"] = "transfer_error";
							}
							$dates = explode(".", $text[2]);
						}
						// Get fullpath
						$filename["fullpath"] = $file;

						$errorvalue = file_get_contents($file);
						if ($errorvalue !== FALSE) {

							$rows = explode("\r\n", $errorvalue);

							$values = array();
							foreach ($rows as $row) {
								if (strlen($row) > 0) {
									if ($filename["category"] == "completion_error") {
										$completion_issue_plant = substr($row, 0, 4);
										$completion_location = substr($row, 4, 4);
										$completion_material_number = str_replace(" ", "", substr($row, 8, 18));
										$completion_lot = str_replace(".000", "", str_replace(" ", "", substr($row, 26, 14)));
										$completion_date = substr($row, 40, 8);
										$date = substr($completion_date, 0, 2);
										$month = substr($completion_date, 2, 2);
										$year = substr($completion_date, 4, 4);
										$mysql_date = $year . '-' . $month . '-' . $date . ' ' . date('H') . ':' . date('i') . ':' . date('s');
										$completion_reference_number = str_replace(" ", "", substr($row, 48, 16));
										$completion_error_description = substr($row, 64, strlen($row) - 63);

										if(strlen($completion_material_number) <= 7){
											$material = self::getMaterial($completion_material_number);
											$valueCompletion["completion_issue_plant"] = $completion_issue_plant;
											$valueCompletion["completion_location"] = $completion_location;
											$valueCompletion["completion_material_id"] = $material->id;
										//$value["completion_material_number"] = $completion_material_number;
											$valueCompletion["lot"] = $completion_lot;
											$valueCompletion["completion_reference_number"] = $completion_reference_number;
											$valueCompletion["error_description"] = $completion_error_description;
											array_push($values, $valueCompletion);

											$historyCompletion['category'] = $filename["category"];
											$historyCompletion['completion_barcode_number'] = "";
											$historyCompletion['completion_description'] = "";
											$historyCompletion['completion_location'] = $valueCompletion["completion_location"];
											$historyCompletion['completion_issue_plant'] = $valueCompletion["completion_issue_plant"];
											$historyCompletion['completion_material_id'] = $material->id;
											$historyCompletion['completion_reference_number'] = $valueCompletion["completion_reference_number"];
											$historyCompletion['error_description'] = $valueCompletion["error_description"];
											$historyCompletion['lot'] = $valueCompletion["lot"];
											$historyCompletion['reference_file'] = $name;
											$historyCompletion['synced'] = 0;
											$historyCompletion['created_at'] = $mysql_date;
											$historyCompletion['updated_at'] = $mysql_date;
											$historyCompletion['user_id'] = 0;
											History::create($historyCompletion);
										}
										else{
											DB::table('error_records')->insert([
												'material' => $completion_material_number,
												'remark' => $name,
												'created_at' => date('Y-m-d H:i:s')
											]);
										}


									}
									else {

										$transfer_document_number = str_replace(" ", "", substr($row, 0, 15));
										$transfer_issue_plant = substr($row, 15, 4);
										$transfer_material_number = str_replace(" ", "", substr($row, 19, 18));
										$transfer_issue_location = substr($row, 37, 4);
										$transfer_receive_plant = substr($row, 41, 4);
										$transfer_receive_location = substr($row, 45, 4);
										$transfer_lot = str_replace(".000", "", str_replace(" ", "", substr($row, 49, 13))) + 0;
										$transfer_cost_center = str_replace(" ", "", substr($row, 62, 10));
										$transfer_gl_account = str_replace(" ", "", substr($row, 72, 10));
										$transfer_date = substr($row, 82, 8);
										$year = substr($transfer_date, 0, 4);
										$month = substr($transfer_date, 4, 2);
										$date = substr($transfer_date, 6, 2);
										$mysql_date = $year . '-' . $month . '-' . $date . ' ' . date('H') . ':' . date('i') . ':' . date('s');
										$transfer_transaction_code = str_replace(" ", "", substr($row, 90, 20));
										$transfer_movement_type = substr($row, 110, 3);
										$transfer_reason_code = str_replace(" ", "", substr($row, 113, 4));
										$transfer_error_description = substr($row, 117, strlen($row) - 117);
										$material = self::getMaterial($transfer_material_number);

										if(strlen($transfer_material_number) <= 7){
											$valueTransfer["transfer_document_number"] = $transfer_document_number;
											$valueTransfer["transfer_issue_plant"] = $transfer_issue_plant;
											$valueTransfer["transfer_material_id"] = $material->id;
											$valueTransfer["transfer_issue_location"] = $transfer_issue_location;
											$valueTransfer["transfer_receive_plant"] = $transfer_receive_plant;
											$valueTransfer["transfer_receive_location"] = $transfer_receive_location;
											$valueTransfer["transfer_cost_center"] = (strlen($transfer_cost_center) > 0 ? $transfer_cost_center : null);
											$valueTransfer["transfer_gl_account"] = (strlen($transfer_gl_account) > 0 ? $transfer_gl_account : null);
											$valueTransfer["transfer_transaction_code"] = (strlen($transfer_transaction_code) > 0 ? $transfer_transaction_code : null);
											$valueTransfer["transfer_movement_type"] = $transfer_movement_type;
											$valueTransfer["transfer_reason_code"] = (strlen($transfer_reason_code) > 0 ? $transfer_reason_code : null);
											$valueTransfer["lot"] = $transfer_lot;
											$valueTransfer["error_description"] = $transfer_error_description;
											array_push($values, $valueTransfer);

											$historyTransfer['category'] = $filename["category"];
											$historyTransfer['transfer_barcode_number'] = "";
											$historyTransfer['transfer_material_id'] = $material->id;
											$historyTransfer['transfer_issue_location'] = $valueTransfer["transfer_issue_location"];
											$historyTransfer['transfer_issue_plant'] = $valueTransfer["transfer_issue_plant"];
											$historyTransfer['transfer_receive_location'] = $valueTransfer["transfer_receive_location"];
											$historyTransfer['transfer_receive_plant'] = $valueTransfer["transfer_receive_plant"];
											$historyTransfer['transfer_transaction_code'] = $valueTransfer["transfer_transaction_code"];
											$historyTransfer['transfer_movement_type'] = $valueTransfer["transfer_movement_type"];
											$historyTransfer['transfer_reason_code'] = $valueTransfer["transfer_reason_code"];
											$historyTransfer['error_description'] = $valueTransfer["error_description"];
											$historyTransfer['lot'] = $valueTransfer["lot"];
											$historyTransfer['reference_file'] = $name;
											$historyTransfer['synced'] = 0;
											$historyTransfer['created_at'] = $mysql_date;
											$historyTransfer['updated_at'] = $mysql_date;
											$historyTransfer['user_id'] = 0;
											History::create($historyTransfer);
										}
										else{
											DB::table('error_records')->insert([
												'material' => $completion_material_number,
												'remark' => $name,
												'created_at' => date('Y-m-d H:i:s')
											]);
										}


									}
								}
							}
							$filename["values"] = $values;
							array_push($filenames, $filename);	
						}
						File::delete(public_path() . "/error_reports/" . $name);
					}			
				}
				// }
				return 'true';
			}
			else {
				return 'false';
			}
		}
		catch(ModelNotFoundException $e) {
			return 'false';
		}
	}

	/**
	 * Get Inventory Lot by Barcode Number
	 *
	 * @param barcode 		barcode number
	 *
	 * @return Inventory
	 *
	 */

	function getMaterial($material_number) {
		$material = DB::table('materials')->where('material_number', $material_number)->first();
		if ($material == null) {
			$data["material_number"] = $material_number;
			$data["description"] = "";
			$data["lead_time"] = 90;
			if (Session::get('id') != null) {
				$data["user_id"] = Session::get('id');
			}
			else {
				$data["user_id"] = 0;
			}
			$material = Material::create($data);
		}
		return $material;
	}

}

?>