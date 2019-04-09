<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class InventoryController extends BaseController {

	/**
	 * Display a list categories page
	 *
	 * @return View
	 */

	public function openFilterPage() {
		return View::make('inventories.filter', array(
			'page' => 'inventories'
		)); 
	}
	
	public function openListPage() {
		$data = Input::all();
		$inventories = self::getFilterInventories($data);
		$fullURL = Request::fullUrl();
		$query = explode("?", $fullURL);

		return View::make('inventories.list', array(
			'page' => 'inventories',
			'inventories' => $inventories,
			'parameter' => $query[1]
		));
	}

	public function exportToExcel() {
		// $inventories = Inventory::where('lot', '>', 0)->orderBy('updated_at', 'ASC')->get();
		$data = Input::all();
		$inventories = self::getFilterInventories($data);
		$data = array(
			'inventories' => $inventories
		);
		// echo json_encode($data);
		// exit();
        Excel::create('Inventory', function($excel) use ($data){
		    $excel->sheet('Inventory', function($sheet) use ($data) {
		        return $sheet->loadView('inventories.excel', $data);
		    });
		})->export('xls');
	}

	function getFilterInventories($data) {
		$inventoryTable = Inventory::where('lot', '>', -1);
		if (isset($data['barcode_number']) && strlen($data['barcode_number']) > 0) {
			if (isset($data['barcode_state'])) {
				if ($data['barcode_state'] == "contain") {
					$inventoryTable = $inventoryTable->where('barcode_number', 'LIKE', '%' . $data['barcode_number'] . '%');
				}
				else {
					$inventoryTable = $inventoryTable->where('barcode_number', '=', $data['barcode_number']);
				}
			}
			else {
				$inventoryTable = $inventoryTable->where('barcode_number', '=', $data['barcode_number']);
			}
		}
		if (isset($data['material_numbers']) && strlen($data['material_numbers']) > 0) {
			$material_numbers = explode(",", $data['material_numbers']);
			$inventoryTable = $inventoryTable->whereIn('material_number', $material_numbers);
		}
		if (isset($data['locations']) && strlen($data['locations']) > 0) {
			$locations = explode(",", $data['locations']);
			$inventoryTable = $inventoryTable->whereIn('issue_location', $locations);
		}
		if (isset($data['description']) && strlen($data['description']) > 0) {
			$inventoryTable = $inventoryTable->where('description', 'LIKE', '%' . $data['description'] . '%');
		}

		// lot_completion
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
			$inventoryTable = $inventoryTable->where('created_at', '>=', $start_date_time);
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
				$time = "00:00";
				$datetime = $date . " " . $time;
				$phpDate = date_create_from_format('m/d/Y H:i', $datetime);
				$mysqlDate = date( 'Y-m-d H:i:s', $phpDate->getTimestamp());
				$end_date_time = $mysqlDate;
			}
			$inventoryTable = $inventoryTable->where('created_at', '<=', $end_date_time);
		}
		$inventories = $inventoryTable->orderBy('updated_at', 'ASC')->get();

		return $inventories;
	}
}