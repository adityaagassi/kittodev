<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingController extends BaseController {

	/**
	 * Display a edit material page
	 *
	 * @return View
	 */

	public function openEditPage() {
		try {
			$setting = Setting::findOrFail(1);
			return View::make('settings.edit', array(
				'page' => 'settings',
				'setting' => $setting
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Update settings
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function updateSetting($id) {

		try {

			$setting = Setting::findOrFail($id);
			$data = Input::all();
			$validator = Validator::make($data, Setting::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$setting->update($data);
			return Redirect::route('settings');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

}