<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\SettingsRequest;
use MixCode\Setting;
use MixCode\ShippingFeePerDistance;

class SettingsController extends Controller
{
    protected $viewPath = 'dashboard.settings';

    public function store(SettingsRequest $request)
    {
        $settings = Setting::first();

        if (!$settings) {

            $settings = Setting::create($request->except('map_url'));

            notify('success', trans('main.added-message'));
        } else {
            $settings->update($request->except('map_url'));

            notify('success', trans('main.updated'));
        }

        if ($request->has('map_url')) {

            $settings->updateMapUrl($request->map_url);
        }

        if ($request->has('logo')) {
            // First param is Request Files, second param postfix key
            $settings->updateSingleMediaFromRequest('logo', 'logo');
        }


        if ($request->has('slider_images')) {
            // First param is Request Files, second param postfix key
            $settings->uploadMultiMediaFromRequest($request->slider_images, 'slider_images');
        }

        return redirect()->route('dashboard.settings.show');
    }

    public function show()
    {
        $sectionName = trans('main.edit') . ' ' . trans('main.settings');

        $settings = Setting::first();

        if (!$settings) {
            $settings = Setting::create(defaultSettings());
        }

        $shipping_fee_per_distances = ShippingFeePerDistance::orderBy("distance_from", "asc")->get();
        
        return view("{$this->viewPath}.settings", compact('sectionName', 'settings','shipping_fee_per_distances'));
    }

    public function deleteMedia(Request $request)
    {
        $settings = Setting::first();

        if (!$settings) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        $settings->deleteMedia($request->media_id);

        cache()->forget('settings');

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }
}
