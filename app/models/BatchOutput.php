<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BatchOutput extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'batchoutputs';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'time' => array('required')
	];
	
}