<?php

class DashboardController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function openDashboardPage()
	{

		// $query = "
		// 	SELECT material_completion.loc AS location, material_completion.barcode_active AS qty, ROUND((material_completion_inventory.barcode_qty/ material_completion.barcode_active) * 100) AS percentage
		// 	FROM 
		// 	(
		// 		SELECT 
		// 		materials.location AS loc, 
		// 		COUNT(completions.barcode_number) AS barcode_active
		// 		FROM completions
		// 		LEFT JOIN materials ON completions.material_id = materials.id
		// 		WHERE completions.active = 1
		// 		GROUP BY materials.location
		// 	) AS material_completion,
		// 	(
		// 		SELECT 
		// 		materials.location AS loc, 
		// 		COUNT(inventories.barcode_number) AS barcode_qty
		// 		FROM inventories
		// 		LEFT JOIN materials ON inventories.material_number = materials.material_number
		// 		WHERE inventories.lot > 0
		// 		GROUP BY materials.location
		// 	) AS material_completion_inventory,
		// 	inventories
		// 	LEFT JOIN completions ON inventories.completion_id = completions.id
		// 	LEFT JOIN materials ON completions.material_id = materials.id
		// 	WHERE material_completion.loc = material_completion_inventory.loc
		// 	GROUP BY location";

		
		$query = "SELECT A.location AS location, CONCAT(A.barcode_store,' of ', B.barcode_all) as qty, ROUND((A.barcode_store/B.barcode_all) * 100) AS percentage FROM
		(
			SELECT issue_location AS location, COUNT(case when lot > 0 then 1 else null end) AS barcode_store FROM inventories GROUP BY issue_location
		) AS A,
		(
			SELECT materials.location AS location, count(barcode_number) AS barcode_all FROM completions LEFT JOIN materials ON materials.id = completions.material_id WHERE active = 1 GROUP BY materials.location
		) AS B
		WHERE A.location = B.location";
				
		$graphpercentages = DB::select( DB::raw( $query ));
		
		return View::make('dashboard', array(
			"page" => "dashboard",
			"graphpercentages" => $graphpercentages
		));
	}

	public function downloadGuide($reference_file) {
		if (file_exists(public_path() . "/guides/" . $reference_file)) {
			header("Content-Length: " . filesize(public_path() . "/guides/" . $reference_file));
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . $reference_file);
			readfile(public_path() . "/guides/" . $reference_file);
			exit();
		}
		else {
			return View::make('404');
		}
	}

	public function openDashboard() {

		$completionStartToday = self::getFirstTimeToday();
		$completionFinishToday = self::getLastTimeToday();
		$completionToday = DB::table('histories')
					->whereBetween('histories.created_at', array($completionStartToday, $completionFinishToday))
					->whereIn('histories.category', 
						array(
							'completion', 
							'completion_adjustment', 
							'completion_adjustment_excel', 
							'completion_adjustment_manual', 
							'completion_cancel', 
							'completion_error',
							'completion_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$completionToday = ($completionToday->count != null ? $completionToday->count : 0);

        $queryCompletionTodays = DB::table('histories')
						->whereBetween('histories.created_at', array($completionStartToday, $completionFinishToday))
						->whereIn('histories.category', 
							array(
								'completion', 
								'completion_adjustment', 
								'completion_adjustment_excel', 
								'completion_adjustment_manual', 
								'completion_cancel', 
								'completion_error',
								'completion_return'
							)
						)
						->select(
							'histories.completion_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.completion_location'
						)
						->get();
		$completionTodayGraphs = array();
		foreach ($queryCompletionTodays as $queryCompletionToday) {
			$today = array();
			array_push($today, $queryCompletionToday->completion_location);
			array_push($today, $queryCompletionToday->count);
			array_push($completionTodayGraphs, $today);
		}

		$completionFirstWeekday = self::getFirstWeekday();
		$completionLastWeekday = self::getLastWeekday();
		$completionWeekly = DB::table('histories')
					->whereBetween('histories.created_at', array($completionFirstWeekday, $completionLastWeekday))
					->whereIn('histories.category', 
						array(
							'completion', 
							'completion_adjustment', 
							'completion_adjustment_excel', 
							'completion_adjustment_manual', 
							'completion_cancel', 
							'completion_error',
							'completion_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$completionWeekly = ($completionWeekly->count != null ? $completionWeekly->count : 0);

        $queryCompletionWeekdays = DB::table('histories')
						->whereBetween('histories.created_at', array($completionFirstWeekday, $completionLastWeekday))
						->whereIn('histories.category', 
							array(
								'completion', 
								'completion_adjustment', 
								'completion_adjustment_excel', 
								'completion_adjustment_manual', 
								'completion_cancel', 
								'completion_error',
								'completion_return'
							)
						)
						->select(
							'histories.completion_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.completion_location'
						)
						->get();
		$completionWeekdayGraphs = array();
		foreach ($queryCompletionWeekdays as $queryCompletionWeekday) {
			$weekday = array();
			array_push($weekday, $queryCompletionWeekday->completion_location);
			array_push($weekday, $queryCompletionWeekday->count);
			array_push($completionWeekdayGraphs, $weekday);
		}

		$completionFirstMonthday = self::getFirstDate();
		$completionLastMonthday = self::getLastDate();
		$completionMonthly = DB::table('histories')
					->whereBetween('histories.created_at', array($completionFirstMonthday, $completionLastMonthday))
					->whereIn('histories.category', 
						array(
							'completion', 
							'completion_adjustment', 
							'completion_adjustment_excel', 
							'completion_adjustment_manual', 
							'completion_cancel', 
							'completion_error',
							'completion_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$completionMonthly = ($completionMonthly->count != null ? $completionMonthly->count : 0);

        $queryCompletionMonthdays = DB::table('histories')
						->whereBetween('histories.created_at', array($completionFirstMonthday, $completionLastMonthday))
						->whereIn('histories.category', 
							array(
								'completion', 
								'completion_adjustment', 
								'completion_adjustment_excel', 
								'completion_adjustment_manual', 
								'completion_cancel', 
								'completion_error',
								'completion_return'
							)
						)
						->select(
							'histories.completion_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.completion_location'
						)
						->get();
		$completionMonthdayGraphs = array();
		foreach ($queryCompletionMonthdays as $queryCompletionMonthday) {
			$monthday = array();
			array_push($monthday, $queryCompletionMonthday->completion_location);
			array_push($monthday, $queryCompletionMonthday->count);
			array_push($completionMonthdayGraphs, $monthday);
		}

		$completionFirstYearday = self::getFirstYearday();
		$completionLastYearday = self::getLastYearday();
		$completionYearly = DB::table('histories')
					->whereBetween('histories.created_at', array($completionFirstYearday, $completionLastYearday))
					->whereIn('histories.category', 
						array(
							'completion', 
							'completion_adjustment', 
							'completion_adjustment_excel', 
							'completion_adjustment_manual', 
							'completion_cancel', 
							'completion_error',
								'completion_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$completionYearly = ($completionYearly->count != null ? $completionYearly->count : 0);

        $queryCompletionYeardays = DB::table('histories')
						->whereBetween('histories.created_at', array($completionFirstYearday, $completionLastYearday))
						->whereIn('histories.category', 
							array(
								'completion', 
								'completion_adjustment', 
								'completion_adjustment_excel', 
								'completion_adjustment_manual', 
								'completion_cancel', 
								'completion_error',
								'completion_return'
							)
						)
						->select(
							'histories.completion_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.completion_location'
						)
						->get();
		$completionYeardayGraphs = array();
		foreach ($queryCompletionYeardays as $queryCompletionYearday) {
			$yearday = array();
			array_push($yearday, $queryCompletionYearday->completion_location);
			array_push($yearday, $queryCompletionYearday->count);
			array_push($completionYeardayGraphs, $yearday);
		}

		$transferStartToday = self::getFirstTimeToday();
		$transferFinishToday = self::getLastTimeToday();
		$transferToday = DB::table('histories')
					->whereBetween('histories.created_at', array($transferStartToday, $transferFinishToday))
					->whereIn('histories.category', 
						array(
							'transfer', 
							'transfer_adjustment', 
							'transfer_adjustment_excel', 
							'transfer_adjustment_manual', 
							'transfer_cancel', 
							'transfer_error',
							'transfer_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$transferToday = ($transferToday->count != null ? $transferToday->count : 0);

        $queryTransferTodays = DB::table('histories')
						->whereBetween('histories.created_at', array($transferStartToday, $transferFinishToday))
						->whereIn('histories.category', 
							array(
								'transfer', 
								'transfer_adjustment', 
								'transfer_adjustment_excel', 
								'transfer_adjustment_manual', 
								'transfer_cancel', 
								'transfer_error',
								'transfer_return'
							)
						)
						->select(
							'histories.transfer_issue_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.transfer_issue_location'
						)
						->get();
		$transferTodayGraphs = array();
		foreach ($queryTransferTodays as $queryTransferToday) {
			$today = array();
			array_push($today, $queryTransferToday->transfer_issue_location);
			array_push($today, $queryTransferToday->count);
			array_push($transferTodayGraphs, $today);
		}

		$transferStartWeekday = self::getFirstWeekday();
		$transferFinishWeekday = self::getLastWeekday();
		$transferWeekly = DB::table('histories')
					->whereBetween('histories.created_at', array($transferStartWeekday, $transferFinishWeekday))
					->whereIn('histories.category', 
						array(
							'transfer', 
							'transfer_adjustment', 
							'transfer_adjustment_excel', 
							'transfer_adjustment_manual', 
							'transfer_cancel', 
							'transfer_error',
							'transfer_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$transferWeekly = ($transferWeekly->count != null ? $transferWeekly->count : 0);

        $queryTransferWeekdays = DB::table('histories')
						->whereBetween('histories.created_at', array($transferStartWeekday, $transferFinishWeekday))
						->whereIn('histories.category', 
							array(
								'transfer', 
								'transfer_adjustment', 
								'transfer_adjustment_excel', 
								'transfer_adjustment_manual', 
								'transfer_cancel', 
								'transfer_error',
								'transfer_return'
							)
						)
						->select(
							'histories.transfer_issue_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.transfer_issue_location'
						)
						->get();
		$transferWeekdayGraphs = array();
		foreach ($queryTransferWeekdays as $queryTransferWeekday) {
			$weekday = array();
			array_push($weekday, $queryTransferWeekday->transfer_issue_location);
			array_push($weekday, $queryTransferWeekday->count);
			array_push($transferWeekdayGraphs, $weekday);
		}

		$transferStartMonthday = self::getFirstDate();
		$transferFinishMonthday = self::getLastDate();
		$transferMonthly = DB::table('histories')
					->whereBetween('histories.created_at', array($transferStartMonthday, $transferFinishMonthday))
					->whereIn('histories.category', 
						array(
							'transfer', 
							'transfer_adjustment', 
							'transfer_adjustment_excel', 
							'transfer_adjustment_manual', 
							'transfer_cancel', 
							'transfer_error',
							'transfer_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$transferMonthly = ($transferMonthly->count != null ? $transferMonthly->count : 0);

        $queryTransferMonthdays = DB::table('histories')
						->whereBetween('histories.created_at', array($transferStartMonthday, $transferFinishMonthday))
						->whereIn('histories.category', 
							array(
								'transfer', 
								'transfer_adjustment', 
								'transfer_adjustment_excel', 
								'transfer_adjustment_manual', 
								'transfer_cancel', 
								'transfer_error',
								'transfer_return'
							)
						)
						->select(
							'histories.transfer_issue_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.transfer_issue_location'
						)
						->get();
		$transferMonthdayGraphs = array();
		foreach ($queryTransferMonthdays as $queryTransferMonthday) {
			$monthday = array();
			array_push($monthday, $queryTransferMonthday->transfer_issue_location);
			array_push($monthday, $queryTransferMonthday->count);
			array_push($transferMonthdayGraphs, $monthday);
		}

		$transferFirstYearday = self::getFirstYearday();
		$transferLastYearday = self::getLastYearday();
		$transferYearly = DB::table('histories')
					->whereBetween('histories.created_at', array($transferFirstYearday, $transferLastYearday))
					->whereIn('histories.category', 
						array(
							'transfer', 
							'transfer_adjustment', 
							'transfer_adjustment_excel', 
							'transfer_adjustment_manual', 
							'transfer_cancel', 
							'transfer_error',
							'transfer_return'
						)
					)
					->select(
						DB::raw('COUNT(*) as count'),
						DB::raw('SUM(histories.lot) as lot')
					)
					->first();
		$transferYearly = ($transferYearly->count != null ? $transferYearly->count : 0);

        $queryTransferYeardays = DB::table('histories')
						->whereBetween('histories.created_at', array($transferFirstYearday, $transferLastYearday))
						->whereIn('histories.category', 
							array(
								'transfer', 
								'transfer_adjustment', 
								'transfer_adjustment_excel', 
								'transfer_adjustment_manual', 
								'transfer_cancel', 
								'transfer_error',
								'transfer_return'
							)
						)
						->select(
							'histories.transfer_issue_location',
							DB::raw('COUNT(*) as count'),
							DB::raw('SUM(histories.lot) as lot')
						)
						->groupBy(
							'histories.transfer_issue_location'
						)
						->get();
		$transferYeardayGraphs = array();
		foreach ($queryTransferYeardays as $queryTransferYearday) {
			$yearday = array();
			array_push($yearday, $queryTransferYearday->transfer_issue_location);
			array_push($yearday, $queryTransferYearday->count);
			array_push($transferYeardayGraphs, $yearday);
		}
		
		return View::make('dashboard', array(
				'completion_today' => $completionToday,
				'completion_weekly' => $completionWeekly,
				'completion_monthly' => $completionMonthly,
				'completion_yearly' => $completionYearly,

				'transfer_today' => $transferToday,
				'transfer_weekly' => $transferWeekly,
				'transfer_monthly' => $transferMonthly,
				'transfer_yearly' => $transferYearly,

				'completion_today_graphs' => $completionTodayGraphs,
				'completion_weekday_graphs' => $completionWeekdayGraphs,
				'completion_monthday_graphs' => $completionMonthdayGraphs,
				'completion_yearday_graphs' => $completionYeardayGraphs,

				'transfer_today_graphs' => $transferTodayGraphs,
				'transfer_weekday_graphs' => $transferWeekdayGraphs,
				'transfer_monthday_graphs' => $transferMonthdayGraphs,
				'transfer_yearday_graphs' => $transferYeardayGraphs
			)
		);
	}

}
