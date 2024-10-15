<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Category;
use MixCode\Product;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class CategoriesController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($categorization_id)
    {
        $categories = Category::active()->where('categorization_id', $categorization_id)->paginate(20);

        return $this->api()->response($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

        $category->load(['products' => function ($query) {
            $query->whereHas('creator', function ($q) {
                $q->byLocation();
            });
        }]);

        return $this->api()->response($category);
    }

    /**
     * List all products from requested category.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function products($category_id, Request $request)
    {

        if ($request->has('store_id') & $request->filled('store_id')) {


            $products = Product::query()
                ->published()
                ->where('creator_id', $request->store_id)
                ->where('category_id', $category_id)
                ->paginate(20);
        } else {

            $products = Product::query()
                ->published()
                ->whereHas('creator', function ($q) {
                    $q->byLocation();
                })
                ->where('category_id', $category_id)
                ->paginate(20);
        }


        return $this->api()->response($products);
    }
}
