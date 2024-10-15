<?php

namespace MixCode\Http\Controllers\Api;

use Artisan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Setting;
use MoaAlaa\ApiResponder\ApiResponder;

class GeneralDataController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Setting $setting, Request $request)
    {

        $data = $setting->getDataInArray($request);

        return $this->api()->response($data);
    }


    /**
     *  update resource.
     *
     * @param  \MixCode\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function updateAppVersion(Request $request)
    {

        $request->validate([
            'app_version' => ['required'] 
        ]);

        $setting  = Setting::first();

        $setting->update([
            'app_version' => $request->app_version 
        ]);
        Artisan::call('cache:clear');


        $data = $setting->getDataInArray($request);

        return $this->api()->response($data, trans('main.updated'), Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    // public function show(Feature $feature)
    // {
    // return $this->api()->response($feature);
    // }
}
