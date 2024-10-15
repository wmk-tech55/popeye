<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use MixCode\Category;
use MixCode\Product;
use MixCode\Utils\UsingSEO;
use MoaAlaa\ApiResponder\ApiResponder;

class CategoriesController extends Controller
{
	use UsingSEO,
		ApiResponder;

	/**
	 * Display the specified resource.
	 *
	 * @param  \MixCode\Category  $category
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$categories = Category::isChild()->active()->get();

		$categories->load(['media', 'creator', 'children', 'parent', 'products']);

		if ($request->wantsJson()) {
            return $this->api()->response($categories);
		}
		
		return 'all categories';
	}
 
}
