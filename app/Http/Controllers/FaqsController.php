<?php

namespace MixCode\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Faq;
use MixCode\Service;
use MixCode\Utils\UsingSEO;

class FaqsController extends Controller
{
    use UsingSEO;

    public function index()
    {
        tap(trans('main.faqs'), function ($seoTitle) {
            $this->seo()->generate(['title' => $seoTitle, 'description' => getSettings()->name_by_lang . ' ' . $seoTitle]);
        });

        $faqs = Faq::active()->paginate(20);
        
        return view('faqs', compact('faqs'));
    }
}