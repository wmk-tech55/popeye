<?php

namespace MixCode\Http\Controllers;

 use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\SubscribesRequest;
use MixCode\Subscribe;

 
class SubscribesController extends Controller
{
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscribesRequest $request)
    {
         Subscribe::create($request->all());
        
         notify('success', trans('main.added-message'));

         return redirect()->route('home');
    }
 
 
   
}
