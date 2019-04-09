<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class HistoryController extends BaseController {

	/**
	 * Display a list histories page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		$histories = History::with(array('user'))
							->orderBy('created_at', 'DESC')
							->get();
		return View::make('histories.list', array(
			'page' => 'histories',
			'histories' => $histories
		));
	}
	
}