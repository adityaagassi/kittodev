<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class CompletionAdjustment extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'completions_adjustment';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'location_completion' => array('required', 'min:1'),
		'issue_plant' => array('required', 'min:1'),
		'lot_completion' => array('required', 'numeric'),
		'material_id' => array('required', 'min:1')
	];

	public function material() {
        return $this->belongsTo('Material')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
}