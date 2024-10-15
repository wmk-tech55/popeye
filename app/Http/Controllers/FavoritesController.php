<?php

namespace MixCode\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Livewire\Favorite;
use MixCode\Product;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class FavoritesController extends Controller
{
    use ApiResponder;

    /**
     * list all  Favorite.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $favoriteProducts = $user->getUserFavorites();

        return view("wishlist", compact('favoriteProducts') );
    }



    /**
     * Mark Product as Favorite.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function markAsFavorite(Product $product)
    {
        $product->markAsFavorite();

        return $this->api()->response($product, trans('site.product_favorited'), Response::HTTP_CREATED);
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

        return $this->api()->response($product, trans('site.product_removed_from_wishlist'), Response::HTTP_OK);
    }
}
