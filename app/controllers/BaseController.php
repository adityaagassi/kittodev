<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	function getSlug($data) {
	   $slug = str_replace(" ", "-", preg_replace('/[«»“”!?,.]+/', '', $data));
	   return strtolower($slug);
	}

	function getTimestamp() {
		$date = new DateTime();
		return $date->getTimestamp();
	}

	function isSupermanOrOwner() {
		if(Session::has('level_id') && Session::get('level_id') < 3) {
			return true;
		}
		return false;
	}

	function getIPAddress() {
		$ip = getenv('HTTP_CLIENT_IP')?:
			getenv('HTTP_X_FORWARDED_FOR')?:
			getenv('HTTP_X_FORWARDED')?:
			getenv('HTTP_FORWARDED_FOR')?:
			getenv('HTTP_FORWARDED')?:
			getenv('REMOTE_ADDR');
		return $ip;
	}

	function isLoggedIn($userID, $ipAddress) {
		$session = self::getSessionData($userID);
		if ($session) {
			if ($session->logged_in == 1) {
				if ($session->ip_address == $ipAddress) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	}

	function setLogin($userID, $ipAddress) {
		DB::table('sessions')->where('user_id', $userID)->update(array('logged_in' => 1, 'ip_address' => $ipAddress));
	}

	function setLogout($userID) {
		DB::table('sessions')->where('user_id', $userID)->update(array('logged_in' => 0, 'ip_address' => ''));
	}

	function getSessionData($userID) {
		$session = DB::table('sessions')->where('user_id', $userID)->first();
		return $session;
	}

	function getFirstTimeToday() {
		return date('Y-m-d 00:00:00');
	}

	function getLastTimeToday() {
		return date('Y-m-d 23:59:59');
	}

	function getFirstWeekday() {
		return date('Y-m-d 00:00:00', strtotime('last monday'));
	}

	function getLastWeekday() {
		return date('Y-m-d 23:59:59', strtotime('next sunday'));
	}

	function getFirstDate() {
		return date('Y-m-01 00:00:00');
	}

	function getLastDate() {
		return date('Y-m-t 23:59:59');
	}

	function getFirstYearday() {
		return date('Y-01-01 00:00:00');
	}

	function getLastYearday() {
		return date('Y-12-31 23:59:59');
	}

	function deactivedCompletion($id) {
		DB::table('completions')->where('id', $id)->update(array('active' => 0));
	}

}
