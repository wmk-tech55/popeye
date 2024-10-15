<?php

namespace MixCode\Http\Controllers\Api;

use MixCode\Http\Controllers\Controller;
use MixCode\Categorization;
use MixCode\Product;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class CategorizationsController extends Controller
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
        $categorizations = Categorization::query()
            ->whereHas('products', function ($query) {
                $query->whereHas('creator', function ($q) {
                    $q->byLocation();
                });
            })
            ->oldest('order_field')
            ->paginate(10);

        $categorizations->each(function ($cat) {
            $cat->load(['products' => function ($query) {
                $query->take(2);
            }]);
        });

        return $this->api()->response($categorizations);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function show(Categorization $categorization)
    {

        return $this->api()->response($categorization);
    }

    /**
     * List all products from requested categorization.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function products($categorization_id)
    {
        $products = Product::query()
            ->published()
            ->where('categorization_id', $categorization_id)
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->paginate(20);

        $products->load(['media', 'creator', 'productAdditions']);

        return $this->api()->response($products);
    }

    /**
     * List all stores from requested categorization.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function stores($categorization_id)
    {

        $products = User::query()
            ->active()
            ->typeCompany()
            ->where('categorization_id', $categorization_id)
            ->byLocation()
            ->paginate(20);

        $products->load(['products']);

        return $this->api()->response($products);
    }
}
