<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Completion extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'completions';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'barcode_number' => array('required', 'min:1'),
		'issue_plant' => array('required', 'min:1'),
		'lot_completion' => array('required', 'numeric', 'min:1'),
		'material_id' => array('required', 'min:1'),
		'limit_used' => array('required', 'numeric', 'min:0'),
		'active' => array('required', 'numeric', 'min:0', 'max:1')
	];

	public function material() {
        return $this->belongsTo('Material')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
}