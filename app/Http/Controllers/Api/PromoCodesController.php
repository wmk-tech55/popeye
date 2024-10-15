<?php

namespace MixCode\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\OrdersRequest;
use MixCode\Product;
use MixCode\Cart;
use MixCode\PromoCode;
use MixCode\User;
use MixCode\UserPromoCode;
use MoaAlaa\ApiResponder\ApiResponder;

class PromoCodesController extends Controller
{
	use ApiResponder;

	public function checkPromoCode(Request $request)
	{

		$promoCode = PromoCode::where('code', $request->promo_code)->first();

		abort_unless($promoCode, Response::HTTP_NOT_FOUND);
 
		$date = now()->toDateString();

	    abort_if($promoCode->expiry_date < $date && $promoCode->active(), Response::HTTP_NOT_FOUND, trans('main.promoCode_is_expired'));
 
		$userPromoCode =  UserPromoCode::where('promo_code_id', $promoCode->id)->where('customer_id', auth()->id())->first();

		if ($userPromoCode) {
			abort_if($userPromoCode->available_quantity <= 0, Response::HTTP_NOT_FOUND, trans('main.promoCode_is_expired'));
		}

		return $this->api()->response($promoCode);
	}
}
