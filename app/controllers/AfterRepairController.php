<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class AfterRepairController extends BaseController {

	/**
	 * Display a list return wizard 1 page
	 *
	 * @return View
	 */
	
	public function openCreatePage() {

		$user_id = Session::get('id');

		$issue_locations = DB::table('materials')
					->select('location')
					->orderBy('location', 'asc')
					->groupBy('location')
					->having('location', '<>', '')
					->get();

		$receive_locations = ["SA0R", "FA0R", "LA0R", "VA0R", "FA1R", "LA1R", "SA1R"];

		return View::make('after-repairs.add', array(
			'page' => 'after-repairs',
			'user_id' => $user_id,
			'issue_locations' => $issue_locations,
			'receive_locations' => $receive_locations
		));
	}

	/**
	 * Adjustment Transfer and display success and failed page
	 * Adjustment Manual
	 *
	 * @return View
	 *
	 */

	public function createRepairs() {

		$data = Input::all();
		$data["transaction_code"] = "MB1B";
		$data["document_number"] = "8190";

		/*
		$date = $data['date'];
		$time = $data['time'];
		$datetime = $date . " " . $time;
		$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
		$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
		$data["created_at"] = $mysqlDate;
		*/

		$material_numbers = $data['material_number'];
		$lots = $data['lot'];

		for ($x = 0; $x < count($material_numbers); $x++) {
		    if (strlen($material_numbers[$x]) == 0) {
		    	unset($lots[$x]);
		    }
		}

		for ($y = 0; $y < count($lots); $y++) {
		    if (strlen($lots[$y]) == 0) {
		    	unset($material_numbers[$y]);
		    }
		}

		$data["active"] = 1;
		
		/*
		unset($data['date']);
		unset($data['time']);
		unset($data['hour']);
		unset($data['minute']);
		*/

		$success_transfer = array();
		$success_completion = array();
		$failed = array();

		for ($x = 0; $x < count($material_numbers); $x++) {

			$material = self::getMaterial($material_numbers[$x]);

			if (isset($material)) {

				$transfer_repair_data = array(
					'material_id' => $material->id,
					'issue_location' => $data['issue_location'],
					'issue_plant' => $data['issue_plant'],
					'receive_location' => $data['receive_location'],
					'receive_plant' => $data['receive_plant'],
					'movement_type' => $data['movement_type'],
					'transaction_code' => $data['transaction_code'],
					'document_number' => $data['document_number'],
					'lot' => $lots[$x],
					'cost_center' => "",
					'gl_account' => "",
					'reason_code' => "",
					'user_id' => $data["user_id"],
					'active' => $data["active"]
					// ,
					// 'created_at' => $data["created_at"]
				);

				//$transfer_repair = ReturnTransfer::create($transfer_repair_data);
				/* // Karena tidak mempengaruhi inventory
				if ($transfer_repair) {
					$inventory = self::getInventoryLot($material_numbers[$x]);
					if ($inventory == null) {
						// insert here
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $lots[$x] * -1;
						$invtentoryData['last_action'] = "transfer_repair";
						$invtentoryData["description"] = $material->description;
						$invtentoryData["issue_location"] = $data['issue_location'];
						Inventory::create($invtentoryData);
					}
					else {
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $inventory->lot - $lots[$x];
						$invtentoryData["last_action"] = "transfer_repair";
						self::updateInventory($inventory->id, $invtentoryData);
					}
				}
				*/
					$history_transfer_repair['category'] = "transfer_repair";
					$history_transfer_repair['transfer_barcode_number'] = "";
					$history_transfer_repair['transfer_document_number'] = $transfer_repair_data['document_number'];
					$history_transfer_repair['transfer_material_id'] = $transfer_repair_data['material_id'];
					$history_transfer_repair['transfer_issue_location'] = $transfer_repair_data['issue_location'];
					$history_transfer_repair['transfer_issue_plant'] = $transfer_repair_data['issue_plant'];
					$history_transfer_repair['transfer_receive_plant'] = $transfer_repair_data['receive_plant'];
					$history_transfer_repair['transfer_receive_location'] = $transfer_repair_data['receive_location'];
					$history_transfer_repair['transfer_cost_center'] = "";//$transfer->cost_center;
					$history_transfer_repair['transfer_gl_account'] = "";//$transfer->gl_account;
					$history_transfer_repair['transfer_transaction_code'] = $transfer_repair_data['transaction_code'];
					$history_transfer_repair['transfer_movement_type'] = $transfer_repair_data['movement_type'];
					$history_transfer_repair['transfer_reason_code'] = "";//$transfer->reason_code;
					$history_transfer_repair['lot'] = $transfer_repair_data['lot'];
					$history_transfer_repair['synced'] = 0;
					// $history_transfer_repair['created_at'] = $mysqlDate;
					if (isset($data['user_id'])) {
						$history_transfer_repair['user_id'] = $data['user_id'];
					}
					if($data['movement_type'] == "9I4"){
							$history_transfer_repair['category'] = "transfer_after_repair";
					}
					$transfer_repair = History::create($history_transfer_repair);
					array_push($success_transfer, $material->material_number);

				$material_with_location = self::getMaterial_with_location($material_numbers[$x], $data['issue_location']);

				if(isset($material_with_location)){
					$completion_repair_data = array(
						'material_id' => $material->id,
						'reference_number' => "",
						'description_completion' => "",
						'location_completion' => $data['issue_location'],
						'issue_plant' => $data['issue_plant'],
						'lot_completion' => $lots[$x],
						'user_id' => $data["user_id"],
						'active' => $data["active"]
						// ,
						// 'created_at' => $data["created_at"]
					);

					//$completion_repair = ReturnCompletion::create($completion_repair_data);
					/* // Karena tidak mempengaruhi inventory
					if ($completion_repair) {
						$inventory = self::getInventoryLot($material_numbers[$x]);
						if ($inventory == null) {
							// insert here
							$invtentoryData["material_number"] = $material_numbers[$x];
							$invtentoryData["lot"] = $lots[$x];
							$invtentoryData['last_action'] = "completion_repair";
							$invtentoryData["description"] = $material->description;
							$invtentoryData["issue_location"] = $data['issue_location'];
							Inventory::create($invtentoryData);
						}
						else {
							$invtentoryData["material_number"] = $material_numbers[$x];
							$invtentoryData["lot"] = $inventory->lot + $lots[$x];
							$invtentoryData["last_action"] = "completion_repair";
							self::updateInventory($inventory->id, $invtentoryData);
						}
					}
					*/
						$history_completion_repair['category'] = "completion_repair";
						$history_completion_repair['completion_barcode_number'] = "";
						$history_completion_repair['completion_description'] = "";
						$history_completion_repair['completion_location'] = $completion_repair_data['location_completion'];
						$history_completion_repair['completion_issue_plant'] = $completion_repair_data['issue_plant'];
						$history_completion_repair['completion_material_id'] = $completion_repair_data['material_id'];
						$history_completion_repair['completion_reference_number'] = $completion_repair_data['reference_number'];
						$history_completion_repair['lot'] = $completion_repair_data['lot_completion'];

						$history_completion_repair['synced'] = 0;
						// $history_completion_repair['created_at'] = $mysqlDate;
						if (isset($data['user_id'])) {
							$history_completion_repair['user_id'] = $data['user_id'];
						}

						if($data['movement_type'] == "9I4"){
							$history_completion_repair['category'] = "completion_after_repair";
							$history_completion_repair['lot'] = $history_completion_repair['lot'] * -1;
						}
						$completion_repair =History::create($history_completion_repair);

						if ($completion_repair) {
							array_push($success_completion, $material->material_number);
						}

				}

				
			}
			else {
				array_push($failed, $material_numbers[$x]);
			}
		}


		$response = array(
			'success_completion' => $success_completion,
			'success_transfer' => $success_transfer,
			'failed' => $failed 
		);

		return View::make('after-repairs.list', array(
			'page' => 'after-repairs',
			'response' => $response
		));
	}

	/**
	 * Get Material
	 *
	 * @param material_number
	 * @param location
	 *
	 * @return Material
	 *
	 */

	function getMaterial($material_number) {
		try {
			$material = Material::where('material_number', '=', $material_number)
						->firstOrFail();
			return $material;
		}
		catch(ModelNotFoundException $e) {
			return null;
		}
	}

	function getMaterial_with_location($material_number, $location) {
		try {
			$material = Material::where('material_number', '=', $material_number)
						->where('location', '=', $location)
						->firstOrFail();
			return $material;
		}
		catch(ModelNotFoundException $e) {
			return null;
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

	function getInventoryLotByCompletionID($completion_id) {
		$inventories = DB::table('inventories')->where('completion_id', $completion_id)->first();
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

	function getInventoryLotByTransferID($transfer_id) {
		$inventories = DB::table('inventories')->where('transfer_id', $transfer_id)->first();
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

	function getInventoryLot($material_number) {
		$inventories = DB::table('inventories')->where('material_number', $material_number)->first();
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