<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Material extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'materials';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'material_number' => array('required', 'min:3'),
		'location' => array('required', 'min:3'),
		'lead_time' => array('required', 'numeric', 'min:1')
	];

	public function completions() {
        return $this->hasMany('Completion')->withTrashed();
    }

	public function completionsAdjustment() {
        return $this->hasMany('CompletionAdjustment')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
    
}