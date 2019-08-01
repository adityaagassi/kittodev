<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class TurnOverController extends BaseController {

	public function openFilterPage() {
		$locations = DB::table('materials')->select('location')->distinct()->get();
		return View::make('turnover.filter', array(
			'page' => 'turnover',
			'locations' => $locations
		));
	}

	public function openFilterListPage() {
		$data = Input::all();
		$fullURL = Request::fullUrl();
		$query = explode("?", $fullURL);
		$max = 0;
		$min = 0;
		$avg = 0;
		$active = 0;
		$turnovers = self::getFilterTurnOvers($data);

		if ($turnovers) {
			$locations = array();
			$materialnumbers = array();
			foreach ($turnovers as $turnover) {
				if (!in_array($turnover->location, $locations)) {
					array_push($locations, $turnover->location);
				}
				if (!in_array($turnover->material_number, $materialnumbers)) {
					array_push($materialnumbers, $turnover->material_number);
				}
			}
			$active = DB::table('completions')->join('materials', 'completions.material_id', '=', 'materials.id');
			if (count($locations) > 0) {
				$active = $active->whereIn('materials.location', $locations);
			}
			if (count($materialnumbers) > 0) {
				$active = $active->whereIn('materials.material_number', $materialnumbers);	
			}
			$active = $active->where('completions.active', '=', 1)->count();
			$max = $turnovers[0]->cycle;
			$min = $turnovers[count($turnovers)-1]->cycle;
			$sum = 0;
			foreach ($turnovers as $turnover) {
				$sum += $turnover->cycle;
			}
			$avg = floor(($sum / count($turnovers)));//floor number_format(($sum / count($turnovers)), 2);
		}

		return View::make('turnover.list', array(
			'page' => 'turnover',
			'max' => $max,
			'min' => $min,
			'active' => $active,
			'avg' => $avg,
			'turnovers' => $turnovers,
			'parameter' => $query[1]
		));

	}

	function getFilterTurnOvers($data) {

		$location = "";
		if (isset($data['location']) && strlen($data['location']) > 0) {
			$location = " AND materials.location = '" . $data['location'] . "'";
		}

		$material_numbers = "";
		if (isset($data['material_numbers']) && strlen($data['material_numbers']) > 0) {
			$materialnumbers = explode(",", $data['material_numbers']);
			$numbers = "";
			foreach ($materialnumbers as $materialnumber) {
				$numbers .= "'". $materialnumber ."'";
			}
			$numbers = str_replace("''","','", $numbers);
			$material_numbers = " AND materials.material_number IN (" . $numbers . ")";
		}

		$startFrom = "";
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
			$startFrom = " AND histories.created_at >= '" . $start_date_time . "'";
		}
		$endUntil = "";
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
			$endUntil = " AND histories.created_at <= '" . $end_date_time . "'";
		}

		$query = "SELECT A_OK.location, A_OK.material_number, A_OK.description, A_OK.completion_barcode_number AS barcode_number, A_OK.completion_lot, A_OK.completion_ok as completion, B_OK.transfer_lot, FLOOR(B_OK.transfer_ok) as transfer, FLOOR(B_OK.transfer_ok) AS 'cycle' FROM (
				SELECT A.location, A.material_number, A.description, A.completion_barcode_number, SUM(A.completion_sum) as completion_lot, SUM(A.completion_cnt) as completion_ok FROM (
					SELECT
			        	materials.location,
			        	materials.description,
			        	materials.material_number,
						histories.completion_barcode_number,
						SUM(histories.lot) AS completion_sum,
						COUNT(histories.lot) AS completion_cnt
					FROM
						histories
			        LEFT JOIN
			        		materials
			        	ON
			        		materials.id = histories.completion_material_id
					WHERE
						histories.category = 'completion'"
						. $location
						. $material_numbers
						. $startFrom
						. $endUntil
						.
					" GROUP BY
						histories.completion_barcode_number
					UNION ALL
					SELECT
			        	materials.location,
			        	materials.description,
			        	materials.material_number,
						histories.completion_barcode_number,
						SUM(histories.lot) AS completion_sum,
						COUNT(histories.lot)*-1 AS completion_cnt
					FROM
						histories
			        LEFT JOIN
			        		materials
			        	ON
			        		materials.id = histories.completion_material_id
					WHERE
						histories.category = 'completion_cancel'"
						. $location
						. $material_numbers
						. $startFrom
						. $endUntil
						.
					" 
					GROUP BY
						histories.completion_barcode_number
				) AS A
				GROUP BY A.completion_barcode_number
			) AS A_OK, (
				SELECT B_1.material_number, completions.barcode_number, B_1.transfer_ok as transfer_lot, B_1.transfer_ok / completions.lot_completion as 'transfer_ok' FROM (
					SELECT B.material_number, B.transfer_barcode_number, SUM(B.transfer_cnt) as 'transfer_ok' FROM (
						SELECT
				        	materials.material_number,
							histories.transfer_barcode_number,
							SUM(histories.lot) AS transfer_cnt
						FROM
							histories
				        LEFT JOIN
			    	    		materials
			        		ON
			        			materials.id = histories.transfer_material_id
						WHERE
							histories.category = 'transfer'"
						. $location
						. $material_numbers
						. $startFrom
						. $endUntil
						.
					" 
						GROUP BY
							histories.transfer_barcode_number
						UNION ALL
						SELECT
				        	materials.material_number,
							histories.transfer_barcode_number,
							SUM(histories.lot)*-1 AS transfer_cnt
						FROM
							histories
				        LEFT JOIN
			    	    		materials
			        		ON
			        			materials.id = histories.transfer_material_id
						WHERE
							histories.category = 'transfer_cancel'"
						. $location
						. $material_numbers
						. $startFrom
						. $endUntil
						.
					" 
						GROUP BY
							histories.transfer_barcode_number
					) AS B
					GROUP BY
						B.transfer_barcode_number
				) AS B_1
				LEFT JOIN transfers ON B_1.transfer_barcode_number = transfers.barcode_number_transfer
				LEFT JOIN completions ON completions.id = transfers.completion_id
			) AS B_OK 
			WHERE 
				A_OK.completion_barcode_number = B_OK.barcode_number
			ORDER BY
				cycle
			DESC";

		$result = DB::select( DB::raw( $query ));

		return $result;
	}

}