<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends BaseController {

	/**
	 * Display a list products page
	 *
	 * @return View
	 */
	
	public function openListPage() {
		$products = Product::with(array('category'))
							->orderBy('category_id', 'ASC')
							->orderBy('name', 'ASC')
							->get();
		return View::make('products.list', array(
			'page' => 'products',
			'products' => $products
		));
	}

	/**
	 * Display a add product page
	 *
	 * @return View
	 */

	public function openAddPage() {
		$categories = Category::orderBy('name', 'ASC')->get();
		return View::make('products.add', array(
			'page' => 'products',
			'categories' => $categories
		));
	}

	/**
	 * Save product
	 *
	 * @return
	 */

	public function createProduct() {

		// Product
		$data = Input::all();
		$data['slug'] = self::getSlug($data['name']);
		// var_dump($data);
		// exit();
		$validator = Validator::make($data, Product::$rules);
		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}
		$product = Product::create($data);

		// History
		$history['user_id'] = Session::get('id');
		$history['description'] = "Create new product : <br>- " . $data['name'];
		$history['url'] = url("products/$product->id");
		History::create($history);

		return Redirect::route('products');
	}

	/**
	 * Display a detail product page
	 * 
	 * @param   $id identity of product
	 *
	 * @return View
	 */

	public function openDetailPage($id) {
		try {
			$product = Product::findOrFail($id);
			return View::make('products.detail', array(
				'page' => 'products',
				'product' => $product
			));
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Display a edit product page
	 *
	 * @return View
	 */

	public function openEditPage($id) {
		try {
			$product = Product::findOrFail($id);
			$categories = Category::orderBy('name', 'ASC')->get();
			return View::make('products.edit', array(
				'page' => 'products',
				'product' => $product,
				'categories' => $categories,
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

	public function updateProduct($id) {

		try {
			// Product
			$product = product::findOrFail($id);
			$data = Input::all();
			$data['slug'] = self::getSlug($data['name']);
			$validator = Validator::make($data, Product::$rules);
			if ($validator->fails()) {
				return Redirect::back()->withErrors($validator)->withInput();
			}
			$description = "Update product : <br>";
			if ($product->name != $data['name']) {
				$description .= "- Product name from " . $product->name . " to " . $data['name'] . "<br>";
			}
			if ($product->quantity != $data['quantity']) {
				$description .= "- Quantity from " . $product->quantity . " to " . $data['quantity'] . "<br>";
			}
			if ($product->category_id != $data['category_id']) {
				$description .= "- Category ID from " . $product->category_id . " to " . $data['category_id'] . "<br>";
			}
			if ($product->sell_price != $data['sell_price']) {
				$description .= "- Sell price from Rp. " . $product->sell_price . " to Rp. " . $data['sell_price'] . "<br>";
			}
			if ($product->buy_price != $data['buy_price']) {
				$description .= "- Product name from Rp. " . $product->buy_price . " to Rp. " . $data['buy_price'] . "<br>";
			}
			$product->update($data);

			// History
			$history['user_id'] = Session::get('id');
			$history['description'] = $description;
			$history['url'] = url("products/$product->id");
			History::create($history);

			return Redirect::route('products');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Delete product
	 * 
	 * @param   $id identity of product
	 *
	 * @return
	 */

	public function deleteProduct($id) {

		try {
			// Product
			$product = Product::findOrFail($id);
			$product->delete();

			// History
			$history['user_id'] = Session::get('id');
			$history['description'] = "Delete product : <br>- " . $product->name;
			$history['url'] = url("products/$product->id");
			History::create($history);

			return Redirect::route('products');
		}
		catch(ModelNotFoundException $e) {
			return View::make('404');
		}
	}

	/**
	 * Get all products json
	 *
	 * @return JSON
	 */
	
	public function productsJSON() {
		$products = Product::orderBy('name', 'ASC')->get();
		$response = array(
			'status' => true, 
			'data' => $products
		);
		return Response::json($response);
	}
}