<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Setting extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'settings';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'upload_resume' => array('required'),
		'download_report' => array('required')
	];
}