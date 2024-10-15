<?php

namespace MixCode\Http\Controllers;

use MixCode\Utils\UsingSEO;

class HomeController extends Controller
{
    use UsingSEO;

    public function index()
    {
        tap(trans('site.home'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

 
        return view('index');
    }



    public function accountStatus()
    {
        tap(trans('site.account_status'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        return view('account-status');
    }


    public function about()
    {
        tap(trans('site.about_us'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        return view('about-us');
    }

    public function termsAndConditions()
    {
        tap(trans('site.terms_and_conditions'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        return view('terms-and-conditions');
    }

    public function privacyPolicy()
    {
        tap(trans('site.privacy_policy'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name . ' ' . $seoTitle]);
        });

        return view('privacy-policy');
    }

    protected function getRecommendProducts()
    {
        // Save Visitor Country Code to not call "visitorCountryCode()" many times and consume high resources
        $visitorsCountryCode = visitorCountryCode();

        return  Product::published()
            ->with(['categories', 'creator']) //, 'favorites'
            ->take(6)
            ->get();
    }
}
