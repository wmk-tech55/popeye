<?php

namespace MixCode\Http\Controllers\Api;

use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MoaAlaa\ApiResponder\ApiResponder;

class DriversController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = User::query()
            ->typeDriver()
            ->active()
            ->byLocation()
            ->byCountry()
            ->paginate(20);

             
        return $this->api()->response($drivers);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = User::findOrFail($id);
 
        return $this->api()->response($driver);
    }

 


    
}
