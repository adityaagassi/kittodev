<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Product extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'products';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'name' => array('required', 'min:3'),
		'slug' => array('required', 'min:3'),
		'quantity' => array('required', 'numeric', 'min:1'),
		'sell_price' => array('required', 'numeric', 'min:1'),
		'buy_price' => array('required', 'numeric', 'min:1'),
		'category_id' => array('required', 'min:1')
	];

	public function category() {
        return $this->belongsTo('Category')->withTrashed();
    }
}