<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReturnController extends BaseController {

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

		$receive_locations = DB::table('transfers')
					->leftJoin('materials', 'transfers.material_id', '=', 'materials.id')
					->select(
						'transfers.receive_location AS location'
					)
					->orderBy('receive_location', 'asc')
					->groupBy('receive_location')
					->get();

		return View::make('returns.add', array(
			'page' => 'returns',
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

	public function createReturn() {

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

		$success = array();
		$failed = array();

		for ($x = 0; $x < count($material_numbers); $x++) {

			$material = self::getMaterial($material_numbers[$x], $data['issue_location']);

			if (isset($material)) {

				$transfer_return_data = array(
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

				$transfer_return = ReturnTransfer::create($transfer_return_data);
				/* // Karena tidak mempengaruhi inventory
				if ($transfer_return) {
					$inventory = self::getInventoryLot($material_numbers[$x]);
					if ($inventory == null) {
						// insert here
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $lots[$x] * -1;
						$invtentoryData['last_action'] = "transfer_return";
						$invtentoryData["description"] = $material->description;
						$invtentoryData["issue_location"] = $data['issue_location'];
						Inventory::create($invtentoryData);
					}
					else {
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $inventory->lot - $lots[$x];
						$invtentoryData["last_action"] = "transfer_return";
						self::updateInventory($inventory->id, $invtentoryData);
					}
				}
				*/
				$history;
				if ($transfer_return) {
					$history_transfer_return['category'] = "transfer_return";
					$history_transfer_return['transfer_barcode_number'] = "";
					$history_transfer_return['transfer_document_number'] = $transfer_return->document_number;
					$history_transfer_return['transfer_material_id'] = $transfer_return->material_id;
					$history_transfer_return['transfer_issue_location'] = $transfer_return->issue_location;
					$history_transfer_return['transfer_issue_plant'] = $transfer_return->issue_plant;
					$history_transfer_return['transfer_receive_plant'] = $transfer_return->receive_plant;
					$history_transfer_return['transfer_receive_location'] = $transfer_return->receive_location;
					$history_transfer_return['transfer_cost_center'] = "";//$transfer->cost_center;
					$history_transfer_return['transfer_gl_account'] = "";//$transfer->gl_account;
					$history_transfer_return['transfer_transaction_code'] = $transfer_return->transaction_code;
					$history_transfer_return['transfer_movement_type'] = $transfer_return->movement_type;
					$history_transfer_return['transfer_reason_code'] = "";//$transfer->reason_code;
					$history_transfer_return['lot'] = $transfer_return->lot;
					$history_transfer_return['synced'] = 0;
					// $history_transfer_return['created_at'] = $mysqlDate;
					if (isset($data['user_id'])) {
						$history_transfer_return['user_id'] = $data['user_id'];
					}
					$history = History::create($history_transfer_return);
				}

				$completion_return_data = array(
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

				$completion_return = ReturnCompletion::create($completion_return_data);
				/* // Karena tidak mempengaruhi inventory
				if ($completion_return) {
					$inventory = self::getInventoryLot($material_numbers[$x]);
					if ($inventory == null) {
						// insert here
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $lots[$x];
						$invtentoryData['last_action'] = "completion_return";
						$invtentoryData["description"] = $material->description;
						$invtentoryData["issue_location"] = $data['issue_location'];
						Inventory::create($invtentoryData);
					}
					else {
						$invtentoryData["material_number"] = $material_numbers[$x];
						$invtentoryData["lot"] = $inventory->lot + $lots[$x];
						$invtentoryData["last_action"] = "completion_return";
						self::updateInventory($inventory->id, $invtentoryData);
					}
				}
				*/
				if ($completion_return) {
					$history_completion_return['category'] = "completion_return";
					$history_completion_return['completion_barcode_number'] = "";
					$history_completion_return['completion_description'] = "";
					$history_completion_return['completion_location'] = $completion_return->location_completion;
					$history_completion_return['completion_issue_plant'] = $completion_return->issue_plant;
					$history_completion_return['completion_material_id'] = $completion_return->material_id;
					$history_completion_return['completion_reference_number'] = $completion_return->reference_number;
					$history_completion_return['lot'] = $completion_return->lot_completion * -1;
					$history_completion_return['synced'] = 0;
					// $history_completion_return['created_at'] = $mysqlDate;
					if (isset($data['user_id'])) {
						$history_completion_return['user_id'] = $data['user_id'];
					}
					History::create($history_completion_return);
				}

				if ($transfer_return && $completion_return) {
					array_push($success, $material->material_number);
				}
				
			}
			else {
				array_push($failed, $material_numbers[$x]);
			}
		}

		$response = array(
			'success' => $success,
			'failed' => $failed 
		);

		return View::make('returns.list', array(
			'page' => 'returns',
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

	function getMaterial($material_number, $location) {
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