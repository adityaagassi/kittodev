<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class TransferAdjustment extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'transfers_adjustment';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'material_id' => array('required', 'min:1'),
		'issue_location' => array('required', 'min:4'),
		'issue_plant' => array('required', 'min:4'),
		'receive_plant' => array('required', 'min:1'),
		'receive_location' => array('required', 'min:1'),
		'lot' => array('required', 'numeric', 'min:1'),
		'transaction_code' => array('required', 'min:1'),
		'movement_type' => array('required', 'min:1')
	];

	public function material() {
        return $this->belongsTo('Material')->withTrashed();
    }

	public function user() {
        return $this->belongsTo('User')->withTrashed();
    }
}