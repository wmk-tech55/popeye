<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Product;
use MixCode\Utils\UsingSEO;
use MoaAlaa\ApiResponder\ApiResponder;

class ProductsController extends Controller
{
    use ApiResponder, UsingSEO;

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        tap(trans('site.products'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });
 

        return view('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        $lang = app()->getLocale();

        if (request()->wantsJson()) {
            $lang = $this->hasLang();
        }

        $field = $lang . '_slug';
    
        $product = Product::where($field,$slug)->firstOrFail();

        tap($product->name_by_lang, function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        $product->load(['media', 'creator', 'reviews',   'classifications' ]);
 
        if (request()->wantsJson()) {
            return $this->api()->response($product);
        }
        
        $other_products      = $product->OtherRelatedProducts();

        $new_arrival_products   = Product::primary()->published()->orderBy('created_at', 'desc')->take(10)->get();

        return view("products.show", compact('product','other_products','new_arrival_products'));
    }

    /**
     * Search And Filter Products
     *
     * @param \Illuminate\Http\request
     * 
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        tap(trans('site.search'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        $products = Product::published()->filter($request)->paginate(4);
 
        $products->load(['media', 'creator',  'categories', 'classifications' ]);

        return $this->api()->response($products);
    }
}
