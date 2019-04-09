<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends BaseController {

	/**
	 * Display a login page
	 *
	 * @return Response
	 */
	
	public function openSigninPage()
	{
		return View::make('signin');
	}

	public function userSignup() {
		$validator = Validator::make(Input::all(), User::$rules);
		if($validator->fails()) {
			return View::make('signup', array(
				'error' => "Invalid Email or Password!"
			));
		}
		$data = Input::except('password_confirmation');
		$data['password'] = Hash::make($data['password']);
		$data['levels_id'] = 3;
		User::create($data);
		return Redirect::route('signin');
	}

	/**
	 * Signin user process
	 *
	 * @return Response
	 */
	
	public function userSignin()
	{
		$rules = array(
			'email' => array('required', 'email', 'min:5'),
			'password' => array('required', 'min:3'),
		);
		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails()) {
			// return Redirect::to('/signin')->withErrors($validator);
			return View::make('signin', array(
				'error' => "Incorrect Email or Password!"
			));
		}
		$user = new User;
		if($user->auth(Input::get('email'), Input::get('password'))) {
			$data = User::getUser(Auth::user()->email);
			if (self::isLoggedIn($data->id, self::getIPAddress())) {
				$session = self::getSessionData($data->id);
				if ($session == null) {
					$sessionData["user_id"] = $data->id;
					$sessionData["ip_address"] = self::getIPAddress();
					$sessionData["logged_in"] = 1;
					$userSession = UserSession::create($sessionData);
					Session::put('session_id', $userSession->id);
				}
				else {
					$ipAddress = self::getIPAddress();
					$sessionData["ip_address"] = $ipAddress;
					$sessionData["logged_in"] = 1;
					self::setLogin($data->id, $ipAddress);
					Session::put('session_id', $session->id);
				}
				Session::put('id', $data->id);
				Session::put('name', $data->name);
				Session::put('email', $data->email);
				Session::put('level_id', $data->level_id);
				return Redirect::route('dashboard');	
			}
			else {
				return View::make('signin', array(
					'error' => "This account status is logged in"
				));
			}
		}
		else {
			return View::make('signin', array(
				'error' => "Incorrect Email or Password!"
			));
		}
	}

	/**
	 * Signout user process
	 *
	 * @return Response
	 */
	public function userSignout()
	{
		$userID = Session::get('id');
		self::setLogout($userID);
		Auth::logout();
        return Redirect::route('signin');
	}

	public function getUserByEmail($email)
	{
		$user = DB::table('user')
				->where('email', $email)
				->first();
		return $user;
	}

	public function userForgot()
	{
		$email = Input::get('email');
		$user = User::getUser($email);
		if (!empty($user)) {
			$data = array(
				"token" => $user->remember_token
			);
			Mail::send('emails.auth.reminder', $data, function($message)
			{
			    $message->to($email)->subject('Forgot Password!');
			});
		}
		else {
			return View::make('forgot', array(
				'error' => "User unregistered."
			));
		}
	}

	public function reset($token) {
		return View::make('reset')->with('token', $token);
	}

	public function resetPassword() {
		$password = Input::get('password');
		$password_confirmation = Input::get('password_confirmation');
		$token = Input::get('token');
		if ($password == $password_confirmation) {
			$user = User::getUserByToken($token);
			$credentials = array(
				'password' => Input::get('password'), 
				'password_confirmation' => Input::get('password_confirmation'), 
				'token' => Input::get('token')
			);
			var_dump($user);
			var_dump($credentials);
			// return Password::reset($credentials, function($user, $password) {
		 //    	$user->password = Hash::make($password);
		 //    	$user->save();
		 //    	return Redirect::to('signin')->with('flash', 'Your password has been reset');
			// });
		}
		else {
			return View::make('reset', array(
				'error' => "New Password and Retype New Password didn't match."
			))->with('token', $token);
		}
	}

	public function updatePassword()
	{
		$password = Input::get('password');
		$password_confirmation = Input::get('password_confirmation');

		$credentials = array(
			'email' => Input::get('email'), 
			'password' => Input::get('password'), 
			'password_confirmation' => Input::get('password_confirmation'), 
			'token' => Input::get('token')
		);
		return Password::reset($credentials, function($user, $password) {
	    	$user->password = Hash::make($password);
	    	$user->save();
	    	return Redirect::to('signin')->with('flash', 'Your password has been reset');
		});
	}

	/**
	 * Display a list products page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		if (self::isSupermanOrOwner()) {
			$users = User::where('level_id', '>', '1')->with(array('level'))->get();
			return View::make('users.list', array(
				'page' => 'users',
				'users' => $users
			));
		}
		else {
			return View::make('404');
		}
	}

	/**
	 * Display a add product page
	 *
	 * @return View
	 */

	public function openAddPage() {
		$levels = Level::where('id', '>', '1')->orderBy('id', 'ASC')->get();
		return View::make('users.add', array(
			'page' => 'users',
			'levels' => $levels
		));
	}

	/**
	 * Save product
	 *
	 * @return
	 */

	public function createUser() {

		// User
		$data = Input::all();
		$validator = Validator::make($data, User::$rules);
		if($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data = Input::except('password_confirmation');
		$data['password'] = Hash::make($data['password']);
		$user = User::create($data);

		return Redirect::route('users');
	}

	/**
	 * Display a detail user page
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 */

	public function openDetailPage($id) {
		try {
			$user = User::with(array('level'))->findOrFail($id);
			return View::make('users.detail', array(
				'page' => 'users',
				'user' => $user
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit user page
	 *
	 * @return View
	 */

	public function openEditPage($id) {
		try {
			$user = User::findOrFail($id);
			$levels = Level::where('id', '>', '1')->orderBy('name', 'ASC')->get();

			if (Session::get('level_id') < 3) {
				return View::make('users.edit', array(
					'page' => 'users',
					'user' => $user,
					'levels' => $levels,
				));
			}
			else if (Session::get('level_id') == $user->level_id && Session::get('id') == $user->id) {
				return View::make('users.edit', array(
					'page' => 'users',
					'user' => $user,
					'levels' => $levels,
				));
			}
			else {
				return View::make('404');
			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Update user
	 * 
	 * @param   $id identity of user
	 *
	 * @return
	 */

	public function updateUser($id) {

		try {
			// Product
			$user = user::findOrFail($id);
			$data = Input::all();
			$validator;
			if (strlen($data['password']) > 0 || strlen($data['password_confirmation']) > 0) {
				$validator = Validator::make($data, User::$rules);
			}
			else {
				$rules = [
					'name' => array('required', 'min:3'),
					'email' => array('required', 'email', 'min:6'),
					'level_id' => array('required', 'min:1')
				];
				$validator = Validator::make($data, $rules);
			}
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			if (strlen($data['password']) > 0 || strlen($data['password_confirmation']) > 0) {
				$data = Input::except('password_confirmation');
				$data['password'] = Hash::make($data['password']);
			}
			else {
				$data = Input::except('password');
				$data = Input::except('password_confirmation');
			}

			$user->update($data);

			if (Session::get('id') == $user->id) {
				return Redirect::to('/users/' . Session::get('id') . '/edit');
			}
			else {
				return Redirect::route('users');
			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete user
	 * 
	 * @param   $id identity of user
	 *
	 * @return
	 */

	public function deleteUser($id) {

		try {

			if (Session::get('level_id') < 3) {
				// User
				$user = User::findOrFail($id);
				$user->delete();

				return Redirect::route('users');
			}
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}











	/**
	 * Display a listing of Users
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = Session::all();
		$userNip = Session::get('nip');
		$user = User::getUser($userNip);
		$petugas = User::all();
		$regions = Region::all();
		return View::make('petugas.index', array(
			'title' => 'Petugas',
			'modul' => 'petugas',
			'user' => $user,
			'petugas' => $petugas,
			'regions' => $regions
		));
	}

	/**
	 * Show the form for creating a new user
	 *
	 * @return Response
	 */
	public function create()
	{
		$userNip = Session::get('nip');
		$user = User::getUser($userNip);
		$regions = Region::all();
		return View::make('petugas.create', array(
			'title' => "Petugas",
			'modul' => 'petugas',
			'user' => $user,
			'regions' => $regions
		));
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$validator = Validator::make($data, User::$rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$data = Input::except('password_confirmation');
		$data['password'] = Hash::make($data['password']);
		if (Input::hasFile('foto') &&  (Input::file('foto')->getMimeType() == "image/jpeg" || Input::file('foto')->getMimeType() == "image/png"))
		{
		    $file = Input::file('foto');
		    $file->move('uploads', $file->getClientOriginalName());
			$data['foto'] = "uploads/" . $file->getClientOriginalName();
		}
		User::create($data);
		return Redirect::route('petugas');
	}

	/**
	 * Display the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$userNip = Session::get('nip');
		$user = User::getUser($userNip);
		$petugas = User::findOrFail($id);
		return View::make('petugas.show', array(
			'title' => "Petugas",
			'modul' => 'petugas',
			'user' => $user,
			'petugas' => $petugas
		));
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$userNip = Session::get('nip');
		$user = User::getUser($userNip);
		$petugas = User::find($id);
		$regions = Region::all();
		return View::make('petugas.edit', array(
			'title' => "Petugas",
			'modul' => 'petugas',
			'user' => $user,
			'petugas' => $petugas,
			'regions' => $regions
		));
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = [
			'nip' => array('required', 'min:1'),
			'nama' => array('required', 'min:3'),
			'telp' => array('required', 'numeric', 'min:6'),
			'email' => array('required', 'email', 'min:6'),
			'password' => array('confirmed', 'min:3'),
			'jabatan' => array('required', 'min:3'),
			'region' => array('required'),
			'foto' => array('image')
		];
		$petugas = User::findOrFail($id);
		$data = Input::all();
		$validator = Validator::make($data, $rules);
		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}
		if (Input::has('password')) {
			$data = Input::except('password_confirmation');
			$data['password'] = Hash::make($data['password']);
		}
		else {
			$data = Input::except('password', 'password_confirmation');
		}
		if (Input::hasFile('foto') && 
			(Input::file('foto')->getMimeType() == "image/jpeg" || Input::file('foto')->getMimeType() == "image/png"))
		{
			$filename = public_path().'/'.$petugas->foto;
			if (File::exists($filename)) {
			    File::delete($filename);
			}
		    $file = Input::file('foto');
		    $file->move('uploads', $file->getClientOriginalName());
			$fotoPath = "uploads/" . $file->getClientOriginalName();
			$data['foto'] = $fotoPath;
		}
		else {
			$data['foto'] = $petugas->foto;
		}
		$petugas->update($data);
		return Redirect::route('petugas');
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$petugas = User::find($id);
		File::delete($petugas->foto);
		$petugas->delete();
		return Redirect::route('petugas');
	}

	public function seeJSONResponse() {
		$data = Input::all();
		$response = array(
			'status' => true,
			'message' => "tes",
			'data' => $data
		);
		return Response::json($response);
	}

}