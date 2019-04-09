<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class History extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'histories';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'lot' => array('required', 'numeric', 'min:1'),
		'synced' => array('required', 'min:0')
	];

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }

	public function material() {
		//completion_material_id
        return $this->belongsTo('Material', 'completion_material_id')->withTrashed();
    }
}