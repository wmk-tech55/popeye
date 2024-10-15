<?php

use MixCode\Country;
use MixCode\User;

function dashboardUrl($url)
{
    return url("/dashboard/{$url}");
}

function userCan($permission)
{
    if (session()->has('user.permissions')) {
        return in_array($permission, session('user.permissions'));
    } else {
        // get the user permissions depending on then roles
        $permissions = auth()->user()->getAllPermissions()->pluck('name')->toArray();
        // save the permissions to can Check on the permissions
        session()->put('user.permissions', $permissions);

        return in_array($permission, session('user.permissions'));
    }
}

function getCartTotalAndFees($carts)
{
    $store              = User::find($carts[0]->store_id);
    $store_shipping_fee = $store->shipping_fee ?? 0;
    $sub_total          = $carts->sum('total_price');
    $final_total        = $sub_total + $store_shipping_fee;

    return [
        'sub_total'   => $sub_total,
        'final_total' => $final_total,
        /* 'shippingFee' => $store_shipping_fee, */
    ];
}

function currency()
{
    return  trans('currency.omr');
}

function getLanguage()
{
    return app()->getLocale();
}

function getLanguageForAssets()
{
    if (!isLang('ar')) return 'en';

    return getLanguage();
}

function isLang($langShortKey)
{
    return getLanguage() == $langShortKey;
}

function getDirection()
{
    return isLang('ar') ? 'rtl' : 'ltr';
}

function getRtlDirection()
{
    return isLang('ar') ? '.rtl' : '';
}

function isRtl()
{
    return isLang('ar') ? true : false;
}

function encodeVar($var)
{
    return json_encode($var, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function defaultSettings()
{
    return config('defaults.settings');
}

// function defaultMainBranch()
// {
//     return config('defaults.main_branch');
// }

function getSettings()
{
    $settings = cache()->rememberForever('settings', function () {
        $settings = \MixCode\Setting::first();

        if (!!$settings) return $settings->load('media');

        return \MixCode\Setting::create(defaultSettings())->load('media');
    });

    return $settings;
}


function isBrowsingFromMobile()
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        return (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPod')) ? true : false;
    }

    return false;
}


function isNotBrowsingFromMobile()
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        return (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPod')) ? false : true;
    }

    return true;
}



// function getMainBranch()
// {
//     $branches = cache()->rememberForever('branches', function () {
//         $branches = \MixCode\Branch::first();

//         if (!! $branches) return $branches;

//         return \MixCode\Branch::create(defaultMainBranch());
//     });

//     return $branches;
// }

function resolveDataValues($data, $forStore = false)
{

    if ($forStore) {

        $data['en_name']     = $data['en_name'] ?? $data['ar_name'];
        $data['ar_name']     = $data['ar_name'] ?? $data['en_name'];
        $data['en_overview'] = $data['en_overview'] ?? $data['ar_overview'];
        $data['ar_overview'] = $data['ar_overview'] ?? $data['en_overview'];
    } else {

        if (array_key_exists('en_name', $data)) {
            $data['en_name']                       = is_null($data['en_name']) ? $data['ar_name'] : $data['en_name'];
        }

        if (array_key_exists('ar_name', $data)) {
            $data['ar_name']                       = is_null($data['ar_name']) ? $data['en_name'] : $data['ar_name'];
        }

        if (array_key_exists('en_overview', $data)) {
            $data['en_overview']                       = is_null($data['en_overview']) ? $data['ar_overview'] : $data['en_overview'];
        }

        if (array_key_exists('ar_overview', $data)) {
            $data['ar_overview']                       = is_null($data['ar_overview']) ? $data['en_overview'] : $data['ar_overview'];
        }
    }

    return $data;
}

function notify($type, $title, $message = '', $notifyType = 'toast')
{
    if ($notifyType == 'alert') {

        alert($title, $message, $type);
    } else if ($notifyType == 'toast') {
        toast($title, $type);
    }
}

function shortCleanString($content, $limit = 100, $end = '...')
{
    if (!!$limit) {
        $content = Str::limit($content, $limit, $end);
    }

    return html_entity_decode(strip_tags(trim($content)));
}


function userLocation()
{
    $latitude  = null;
    $longitude = null;
    $area      = null;
    $distance  = 2;
    $request   = request();

    if ($request->has('latitude') && $request->filled('latitude')) $latitude    = $request->latitude;
    if ($request->has('longitude') && $request->filled('longitude')) $longitude = $request->longitude;
    if ($request->has('area') && $request->filled('area')) $area                = $request->area;
    if ($request->has('distance') && $request->filled('distance')) $distance    = $request->distance;

    $location = [
        'latitude'  => $latitude,
        'longitude' => $longitude,
        'area'      => $area,
        'distance'  => $distance
    ];

    return $location;
}



function visitorCountryCode()
{

    if (!empty(request()->server('HTTP_CLIENT_IP'))) {
        $ip = request()->server('HTTP_CLIENT_IP');
    } else if (!empty(request()->server('HTTP_X_FORWARDED_FOR'))) {
        $ip = request()->server('HTTP_X_FORWARDED_FOR');
    } else {
        $ip = request()->server('REMOTE_ADDR');
    }

    // Store the IP address 
    $ip = '197.37.184.109';

    $code =  @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

    return  optional($code)->geoplugin_countryCode;
}


function countryId()
{
    $auth = auth();

    if (request()->wantsJson()) $auth = auth('api');

    // Reuse User with "web" guard in ajax calls
    if (request()->ajax()) $auth = auth();


    if ($auth->check())  return $auth->user()->active_country_id;

    return (request()->has('active_country_id') && request()->filled('active_country_id')) ? request('active_country_id') : Country::first()->id;
}
