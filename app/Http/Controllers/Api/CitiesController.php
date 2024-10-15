<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\City;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class CitiesController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->api()->response(City::active()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return $this->api()->response($city);        
    }
    
    /**
     * Read Country from requested city.
     *
     * @param  \MixCode\City  $city
     * @return \Illuminate\Http\Response
     */
    public function country(City $city)
    {
        abort_unless($city->country()->exists(), Response::HTTP_NOT_FOUND);
        
        return $this->api()->response($city->country);
    }

    /**
     * List all products from requested city.
     *
     * @param  \MixCode\City  $city
     * @return \Illuminate\Http\Response
     */
    public function products($city_id)
    {
        $products = Product::published()->where('city_id', $city_id)->paginate(20);

        return $this->api()->response($products);
    }
}
