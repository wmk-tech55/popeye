<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ReviewsRequest;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class ReviewsController extends Controller
{
    use ApiResponder;

    /**
     * Store New Review.
     *
     * @param  \MixCode\Product  $product
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitReview(Product $product, Request $request)
    {

        
        $request->validate([
            'review'  => ['required', 'string', 'max:500'],
            'rate'    => ['required', 'integer', 'min:1|max:5'],
        ]);

        $user = auth()->user();

        $data = $request->only(['review', 'rate']);
        $data['name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['customer_id'] = auth()->id();
        
        $product = $product->submitReview($data);

        return $this->api()->response([], trans('main.thanks_for_review'), Response::HTTP_CREATED);
    }

    /**
     * List Product Reviews resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function listProductReview(Product $product)
    {
        abort_unless($product->reviews()->exists(), Response::HTTP_NOT_FOUND);
        
        $reviews = $product->reviews;

        $reviews->load('customer');
       
        return $this->api()->response($reviews);   
    }
}
