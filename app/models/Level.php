<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Level extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'levels';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'name' => array('required', 'min:3')
	];
}