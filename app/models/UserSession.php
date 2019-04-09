<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class UserSession extends Eloquent {

	// use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'sessions';
	// protected $dates = ['deleted_at'];
	public static $unguarded = true;

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
}