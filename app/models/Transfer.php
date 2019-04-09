<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Transfer extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'transfers';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'barcode_number_transfer' => array('required', 'min:1'),
		'issue_location' => array('required', 'min:1'),
		'issue_plant' => array('required', 'min:1'),
		'receive_location' => array('required', 'min:1'),
		'receive_plant' => array('required', 'min:1'),
		'movement_type' => array('required', 'min:1'),
		'lot_transfer' => array('required', 'numeric', 'min:1'),
		'completion_id' => array('required', 'min:1')
	];

	public function completion() {
        return $this->belongsTo('Completion')->withTrashed();
    }

	public function material() {
        return $this->belongsTo('Material')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
    
}