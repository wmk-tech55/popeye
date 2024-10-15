<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\SettingsRequest;
use MixCode\ShippingFeePerDistance;

class ShippingFeePerDistancesController extends Controller
{

    public function store(ShippingFeePerDistance $shippingFeePerDistance, Request $request)
    {

        $shippingFeePerDistance->updateItem($request);

        notify('success', trans('main.updated'));
 
        return redirect()->route('dashboard.settings.show');
    }
}
