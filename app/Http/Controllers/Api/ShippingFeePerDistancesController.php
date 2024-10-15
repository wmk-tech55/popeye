<?php

namespace MixCode\Http\Controllers\Api;

use Artisan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\ShippingFeePerDistance;
use MoaAlaa\ApiResponder\ApiResponder;

class ShippingFeePerDistancesController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ShippingFeePerDistance = ShippingFeePerDistance::orderBy("distance_from", "asc")->get();

        return $this->api()->response($ShippingFeePerDistance);
    }
}
