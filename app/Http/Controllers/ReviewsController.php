<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Requests\ReviewsRequest;
use MixCode\Product;
use MixCode\Utils\UsingSEO;

class ReviewsController extends Controller
{
    use UsingSEO;
    
    public function review(Product $product)
    {
        abort_unless($product->isUserAllowedToReview(), Response::HTTP_UNAUTHORIZED);

        tap(trans('main.submit_review'), function ($seoTitle) use ($product) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => $seoTitle]);
        });
        
        return view('products.review', compact('product'));
    }

    /**
     * Store New Review.
     *
     * @param  \MixCode\Product  $product
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitReview(Product $product, ReviewsRequest $request)
    {
 
        $data = $request->only(['name' ,'email','review', 'rate']);
        
        $product->submitReview($data);

         notify('success', trans('site.review_submitted'));

        return redirect($product->viewPath());
    }

}
