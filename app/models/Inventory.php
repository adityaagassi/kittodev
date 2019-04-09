<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Inventory extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'inventories';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'material_number' => array('required'),
		'lot' => array('required', 'numeric', 'min:1'),
		'last_action' => array('required')
	];

	public function completion() {
        return $this->belongsTo('Completion')->withTrashed();
    }
}