<?php

namespace MixCode\Http\Controllers\Api;

use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class CompaniesController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = User::query()
            ->typeCompany()
            ->active()
            ->byLocation()
            ->byCountry()
            ->paginate(20);

        return $this->api()->response($stores);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = User::findOrFail($id);

        
        $store->load('categorization', 'products', 'work_times');
        
        $store->append('is_open_today');
        
        $categories_id = [];

        foreach ($store->products as $product) {

            $categories_id[] = $product->category->id;
        }

        $categories = Category::whereIn('id', $categories_id)->get();

        $data = [
            'store'      => $store,
            'categories' => $categories
        ];
        return $this->api()->response($data);
    }

    /**
     * List published  products from requested user.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function publishedProducts($store_id)
    {
        $products = Product::query()
            ->with(['media', 'creator', 'productAdditions', 'categorization'])
            ->published()
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->where('creator_id', $store_id)->paginate(20);

        $products->load(['media', 'creator', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }

    /**
     * List   products has discount from requested user.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function productsHasDiscount($store_id)
    {
        $products = Product::query()
            ->with(['media', 'creator', 'productAdditions', 'categorization'])
            ->published()
            ->where('discount_percentage', '>', 0)
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->where('creator_id', $store_id)->paginate(20);

        $products->load(['media', 'creator', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }



    /**
     * List all  products from requested user.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function allProducts($store_id)
    {

        $products = Product::query()
            ->with(['media', 'creator', 'productAdditions', 'categorization'])
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->where('creator_id', $store_id)->paginate(20);


        return $this->api()->response($products);
    }
}
