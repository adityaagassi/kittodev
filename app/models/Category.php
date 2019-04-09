<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Category extends Eloquent {

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	
	protected $table = 'categories';
	protected $dates = ['deleted_at'];
	public static $unguarded = true;
	public static $rules = [
		'name' => array('required', 'min:3'),
		'slug' => array('required', 'min:3')
	];

	public function products() {
        return $this->hasMany('Product')->withTrashed();
    }
}