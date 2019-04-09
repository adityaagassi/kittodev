<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class SessionController extends BaseController {

	/**
	 * Display a list products page
	 *
	 * @return View
	 *
	 */
	
	public function openListPage() {
		$sessions = UserSession::with(array('user'))->where('ip_address','<>','')->get();
		// echo json_encode($sessions);
		// exit();
		return View::make('sessions.list', array(
			'page' => 'sessions',
			'sessions' => $sessions
		));
	}

	/**
	 * Delete product
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 *
	 */

	public function deleteSession($id) {

		try {
			// Product
			$session = UserSession::findOrFail($id);
			if (Session::has('id') && Session::get('id') == $session->user_id) {
				$session->forceDelete();//delete();
				return Redirect::route('logout');
			}
			else {
				$session->forceDelete();//delete();
				return Redirect::route('sessions');
			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

}