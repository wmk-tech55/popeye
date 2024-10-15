<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Favorite;
use MixCode\Http\Controllers\Controller;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class FavoritesController extends Controller
{
    use ApiResponder;

    /**
     * Mark Product as Favorite.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function markAsFavorite(Product $product)
    {  
            $product->markAsFavorite();

        return $this->api()->response([], trans('main.product_has_favorited'), Response::HTTP_CREATED);
    }

    /**
     * Mark Product as Un Favorite.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function markAsUnFavorite(Product $product)
    {
        $product->markAsUnFavorite();

        return $this->api()->response([], trans('main.product_un_favorited'), Response::HTTP_OK);
    }

    /**
     * List Favorite Products.
     *
     * @return \Illuminate\Http\Response
     */
    public function listFavoriteProducts()
    {
        $product = Product::published()->whereHas('favorites', function (Builder $builder) {
            $builder->where('favorites.user_id', auth()->id());
        })->paginate(20);
        $product->load(['media', 'creator',  'classifications' ]);
    
        return $this->api()->response($product);
    }
}
