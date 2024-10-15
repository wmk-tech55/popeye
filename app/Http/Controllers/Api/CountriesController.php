<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Country;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class CountriesController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->api()->response(Country::active()->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        return $this->api()->response($country);   
    }

    /**
     * List all cities from requested country.
     *
     * @param  \MixCode\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function cities(Country $country)
    { 
        abort_unless($country->cities()->exists(), Response::HTTP_NOT_FOUND);

        return $this->api()->response($country->cities);
    }

    /**
     * List all products from requested country.
     *
     * @param  \MixCode\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function products($country_id)
    {
        $products = Product::published()->where('country_id', $country_id)->paginate(20);

        return $this->api()->response($products);
    }
}
