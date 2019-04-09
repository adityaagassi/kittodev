<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends BaseController {

	/**
	 * Display a list categories page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		$categories = Category::with(array('products'))->orderBy('name', 'ASC')->get();
		return View::make('categories.list', array(
			'page' => 'categories',
			'categories' => $categories
		));
	}

	/**
	 * Display a add category page
	 *
	 * @return View
	 */

	public function openAddPage() {
		return View::make('categories.add', array(
			'page' => 'categories'
		));
	}

	/**
	 * Save category
	 *
	 * @return
	 */

	public function createCategory() {

		// Category
		
		$data = Input::all();
		$data['slug'] = self::getSlug($data['name']);
		$validator = Validator::make($data, Category::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$category = Category::create($data);

		return Redirect::route('categories');
	}

	/**
	 * Display a detail category page
	 * 
	 * @param   $id identity of category
	 *
	 * @return View
	 */

	public function openDetailPage($id) {
		try {
			$category = Category::with(array('products'))->findOrFail($id);
			return View::make('categories.detail', array(
				'page' => 'categories',
				'category' => $category
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit category page
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function openEditPage($id) {
		try {
			$category = Category::findOrFail($id);
			return View::make('categories.edit', array(
				'page' => 'categories',
				'category' => $category
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Update category
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function updateCategory($id) {

		try {
			// Category
			$category = Category::findOrFail($id);
			$data = Input::all();
			$data['slug'] = self::getSlug($data['name']);
			$validator = Validator::make($data, Category::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$category->update($data);

			return Redirect::route('categories');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete category
	 * 
	 * @param   $id identity of category
	 *
	 * @return
	 */

	public function deleteCategory($id) {

		try {
			// Category
			$category = Category::findOrFail($id);
			$category->delete();

			return Redirect::route('categories');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}
}