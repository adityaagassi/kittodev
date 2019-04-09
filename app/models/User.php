<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'users';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'name' => array('required', 'min:3'),
		'email' => array('required', 'email', 'min:6'),
		'password' => array('required', 'confirmed', 'min:3'),
		'level_id' => array('required', 'min:1')
	];

	public function level() {
        return $this->belongsTo('Level')->withTrashed();
    }

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	protected $hidden = array('password', 'remember_token');

	/**
	 * Get the authentication user status.
	 *
	 * @return bool
	 */

	public function auth($email, $pass) {

		$userdata = array(
			'email' => $email,
			'password' => $pass
		);
		
		if(Auth::attempt($userdata)) {
			return true;
		}
		else {
			return false;
		}
     }

	/**
	 * Create a new User
	 *
	 * @return User
	 */

	public static function setSignup($data) {
		$data['password'] = Hash::make($data['password']);
		$user = User::create($data);
		return $user;
	}

	/**
	 * Get the User by Email.
	 *
	 * @return User
	 */

	public static function getUser($email) {
		$user = User::withTrashed()
				->where('email', $email)
				->first();

		return $user;
	}

	public static function getUserByToken($token) {
		$user = User::where('remember_token', $token)->first();
		return $user;
	}

	/**
	 * Update User data if exists
	 * Create User data if unregistered
	 *
	 * @return User
	 */

	public static function setUser($data) {

		if(isset($data['id'])) {
			if(isset($data['password']) && Count($data['password']) > 0) {
				$data['password'] = Hash::make($data['password']);
			}
			else {
				unset($data['password']);
			}
			$user = User::find($data['id'])->update($data);
		}
		else {
			$data['password'] = Hash::make($data['password']);
			$user = User::create($data);	
		}
		return $user;	
	}

}
