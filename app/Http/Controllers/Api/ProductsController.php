<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ProductsRequest;
use MixCode\Product;
use MixCode\ProductView;
use MoaAlaa\ApiResponder\ApiResponder;

class ProductsController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products =  Product::query()
            ->published()
            ->byCountry()
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);


        return $this->api()->response($products);
    }

    /**
     * Display a listing of the Top selling Product resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function topSellingProduct($store_id)
    {

        abort_unless($store_id, Response::HTTP_NOT_FOUND, 'store id is required');
        $products =  Product::published()
            ->withCount(['orders' => function ($q) {
                return $q->where('is_cancelled', false);
            }])
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->where('creator_id', $store_id)
            ->byCountry()
            ->orderByDesc('orders_count')
            ->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);


        return $this->api()->response($products);
    }

    /**
     * Display a listing of the Top Rated Product resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function topRatedProduct($store_id)
    {
        abort_unless($store_id, Response::HTTP_NOT_FOUND, 'store id is required');

        $products =  Product::published()
            ->orderByDesc('average_rate')
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->where('creator_id', $store_id)
            ->byCountry()
            ->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function productOffers()
    {
        $products = Product::published()
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->byCountry()
            ->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listByCategorization($categorization_id)
    {
        $products =  Product::published()
            ->where('categorization_id', $categorization_id)
            ->whereHas('creator', function ($q) {
                $q->byLocation();
            })
            ->byCountry()
            ->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }


    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Request $request)
    {
        // Increment Views Counter
        $product->increment('views_count');

        $product->load(['media', 'creator', 'productAdditions', 'categorization']);

        // Record New User View
        if (auth('api')->check()) {
            $product->views()->attach(auth('api')->id());
        }


        return $this->api()->response($product);
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
        $products = Product::published()->byCountry()->filter($request)->paginate(20);

        $products->load(['media', 'productAdditions', 'categorization']);

        return $this->api()->response($products);
    }



    /**
     * List Favorite Products.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function showFavorites(Product $product)
    {
        abort_unless($product->languages()->exists(), Response::HTTP_NOT_FOUND, trans("main.not_found"));

        return $this->api()->response($product->languages);
    }


    /**
     * add new Products.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsRequest $request, Product $product)
    {
        abort_unless(auth()->user()->isAllowed()  && auth()->user()->isActive(), Response::HTTP_UNAUTHORIZED, trans('main.you_are_not_authorized_for_this_action'));

        if (auth()->user()->isCompany()) {

            $request->request->add([
                'categorization_id' => auth()->user()->categorization_id
            ]);

            /* , 'status'            => Product::INACTIVE_STATUS */
        }

        $product = $product->createNewProduct($request);

        //   $product->notifySubscribersForNewProducts();
        $product->notifyAdminForNewProducts();

        if ($request->has('images')) {

            $product->uploadMultiMediaFromRequest('images');
        }

        return $this->api()->response($product, trans('main.added-message'), Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product, ProductsRequest $request)
    {

        abort_unless($product->isAllowedToUpdateOrDelete(), Response::HTTP_UNAUTHORIZED, trans('main.you_are_not_authorized_for_this_action'));

        abort_unless($product, Response::HTTP_NOT_FOUND, trans("main.not_found"));

        if (auth()->user()->isCompany()) {

            $request->request->add([
                'categorization_id' => auth()->user()->categorization_id

            ]);
            //'status'            => Product::INACTIVE_STATUS
        }

        $product          = $product->updateProduct($request);

        $product->notifyAdminForNewProducts('update');

        if ($request->has('images')) {

            $product->uploadMultiMediaFromRequest('images');
        }

        notify('success', trans('main.updated'));


        return $this->api()->response($product, trans('main.updated'), Response::HTTP_OK);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        abort_unless($product->isAllowedToUpdateOrDelete(), Response::HTTP_UNAUTHORIZED, trans('main.you_are_not_authorized_for_this_action'));

        $product->delete();

        return $this->api()->response([], trans('main.deleted-message'), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteMedia(Product $product, $media)
    {
        $product->deleteMedia($media);

        return $this->api()->response([], trans('main.deleted-message'), Response::HTTP_OK);
    }
}
