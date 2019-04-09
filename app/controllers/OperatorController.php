<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class OperatorController extends BaseController {

	/**
	 * Display a operator page
	 *
	 * @return Response
	 */
	
	public function openOperatorPage() {

        // BatchOutputController::resumeCompletion();
        // $batchOutputController = new BatchOutputController();
        // $batchOutputController->resumeCompletion();
        // $batchOutputController->resumeTransfer();
        // exit();
		return View::make('operator');
	}


}