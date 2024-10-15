<?php

namespace MixCode\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\Banner;
use MoaAlaa\ApiResponder\ApiResponder;

class BannersController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products =  Banner::byCountry()->get();

        $products->load(['media']);

        return $this->api()->response($products);
    }
 
  
}
