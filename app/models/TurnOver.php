<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TurnOver extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'turn_overs';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'barcode_number' => array('required'),
		'material_number' => array('required')
	];
}