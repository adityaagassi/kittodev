<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ReturnCompletion extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'completions_return';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'location_completion' => array('required', 'min:1'),
		'issue_plant' => array('required', 'min:1'),
		'lot_completion' => array('required', 'numeric', 'min:1'),
		'material_id' => array('required', 'min:1')
	];

	public function material() {
        return $this->belongsTo('Material')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
}