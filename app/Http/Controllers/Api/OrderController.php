<?php

namespace MixCode\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Address;
use MixCode\CanceledOrder;
use MixCode\Order;
use MixCode\Http\Controllers\Controller;
use MixCode\Http\Requests\ApiOrdersRequest;
use MixCode\Product;
use MixCode\Cart;
use MixCode\City;
use MixCode\Country;
use MixCode\Notifications\OrderIsCanceled;
use MixCode\OrderRequest;
use MixCode\PromoCode;
use MixCode\User;
use MixCode\UserPromoCode;
use MoaAlaa\ApiResponder\ApiResponder;

class OrderController extends Controller
{
	use ApiResponder;

	public function order(ApiOrdersRequest $request, Order $order)
	{
		$carts = auth()->user()->carts;
		$address = Address::query()
			->where('id', $request->address_id)
			->where('owner_id', auth()->id())
			->first();

		abort_unless($carts->isNotEmpty(), Response::HTTP_NOT_FOUND, trans('site.cart_empty'));

		abort_unless($address, Response::HTTP_NOT_FOUND, trans('site.address_not_found'));

		$response = $order->orderFormats($carts);

		$summery  = getCartTotalAndFees($carts);

		$order    = $order->createOrder($response, $request, $summery);

		abort_unless($order, Response::HTTP_NOT_FOUND, trans('site.product_not_available'));


		auth()->user()->carts->each->delete();

		$order->notifyUsersForNewOrder();

		$order->load(['products', 'provider']);

		return $this->api()->response($order, trans('site.order_completed_successfully'), Response::HTTP_CREATED);
	}

	/**
	 * Accept Order Request By the Provider  
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function acceptOrderRequest(Order $order)
	{
		$user = auth('api')->user();

		abort_unless($user->isDriver(), Response::HTTP_UNAUTHORIZED, trans('main.unauthorized'));

		abort_unless(!$user->hasRunningTrip(), Response::HTTP_FORBIDDEN,  trans('main.cannot_accept_order_unless_finished_trip'));

		abort_unless($order->isNotAccepted(), Response::HTTP_FORBIDDEN,  trans('main.order_already_accepted'));

		abort_unless(!$order->requestIsCancelled(), Response::HTTP_FORBIDDEN,  trans('main.cannot_accept_cancelled_order'));

		$order = $order->markAsAccepted();

		auth()->user()->markAsOnTrip();

		$orderRequest = OrderRequest::query()
			->where('driver_id', auth()->id())
			->where('order_id', $order->id)
			->first();

		if ($orderRequest) {
			$orderRequest->markAsAccepted();
		}
		$order->notifyProviderForOrderAccepted();

		return $this->api()->response($order, trans('main.accept_order_request'));
	}


	public function inShipping(Order $order)
	{
		$order->markAsShipped();
		//->notifyCustomerOrderInShipping()
		//->notifyAdminOrderInShipping();

		return $this->api()->response(null, 'Order is in shipping', Response::HTTP_CREATED);
	}

	public function notInShipping(Order $order)
	{
		$order->markAsNotShipped();
		return $this->api()->response(null, 'Order is not in shipping yet', Response::HTTP_CREATED);
	}


	public function delivered(Order $order)
	{
		$order->markAsDelivered();
		//->notifyCustomerOrderIsDelivered()
		//->notifyAdminOrderIsDelivered();

		auth()->user()->markAsFinishTrip();

		return $this->api()->response(null, 'Order is delivered', Response::HTTP_CREATED);
	}

	public function notDelivered(Order $order)
	{
		$order->markAsNotDelivered();

		auth()->user()->markAsOnTrip();

		return $this->api()->response(null, 'Order is not delivered', Response::HTTP_CREATED);
	}

	/**
	 * List all user  cancelled orders.
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function cancelOrder(Order $order)
	{

		$order->update(['is_cancelled' => 1]);

		$products =  $order->products;

		/* 	$products->each(function ($product) use ($order) {

			$original_product = Product::find($product->product_id);

			$quantity = $product->quantity + $original_product->quantity;
			$original_product->update(['quantity' => $quantity]);
		}); */

		//$order->notifyAdminsForCancelOrder();

		return $this->api()->response(null, 'Order is cancelled successfully', Response::HTTP_CREATED);
	}



	/**
	 * Display the specified resource by invoice id.
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function searchByInvoiceId(Request $request)
	{
		$order =  Order::where('invoice_id', $request->invoice_id)->get();

		$order->load('products');
		return $this->api()->response($order);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function show(Order $order)
	{

		/* abort_if($order->customer_id != auth()->id(), Response::HTTP_NOT_FOUND); */
		$order->load('products');
		return $this->api()->response($order);
	}


	/**
	 *    cancelled Request By Provider.
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function cancelRequest(Order $order)
	{

		$order->markAsNotAccepted();

		$data = [
			'driver_id' => auth()->id(),
			'order_id'  => $order->id,
		];

		CanceledOrder::create($data);

		$order->notifyUsersForNewOrder();

		$order->load('canceledRequests');

		return $this->api()->response($order, trans('main.request_is_canceled'), Response::HTTP_CREATED);
	}


	public function paid(Order $order, Request $request)
	{

		abort_unless(auth()->id() === $order->customer_id, Response::HTTP_UNAUTHORIZED);

		$order->markAsPaid()
			->payWith($request->payment_method)
			->paidOn($request->platform);
		//->notifyCreatorForOrderPaid()
		//->notifyCustomerForOrderPaid();

		return $this->api()->response(null, trans('site.payment_complete_message'));
	}


	public function deleteOrder(Order $order)
	{

		$order->markAsDeleted();

		return $this->api()->response(null, trans('main.deleted-message'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function getById(Order $order, Request $request)
	{
		abort_if(!$order, Response::HTTP_NOT_FOUND, trans('main.not_found'));

		return $this->api()->response($order);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \MixCode\Order  $order
	 * @return \Illuminate\Http\Response
	 */
	public function updateStatus(Request $request, Order $order)
	{
		$data = $request->all();

		// mark as approved and send notification to user via database
		if ($request->has('is_approved') && $request->filled('is_approved')) {

			if ($data['is_approved'] == 1 && $order->is_approved == 0) {

				$order->markAsApproved()->notifyCustomerOrderActivated();
			} elseif ($data['is_approved'] == 0 && $order->is_approved == 1) {

				$order->markAsNotApproved();
			}
		}

		if ($request->has('is_preparing') && $request->filled('is_preparing')) {


			if ($data['is_preparing'] == 1 && $order->is_preparing == 0) {

				$order->markAsPrepared()->notifyCustomerOrderIsPreparing();
			} elseif ($data['is_preparing'] == 0 && $order->is_preparing == 1) {

				$order->markAsNotPrepared();
			}
		}

		if ($request->has('in_shipping') && $request->filled('in_shipping')) {


			if ($data['in_shipping'] == 1 && $order->in_shipping == 0) {

				$order->markAsShipped()->notifyCustomerOrderInShipping();
			} elseif ($data['in_shipping'] == 0 && $order->in_shipping == 1) {

				$order->markAsNotShipped();
			}
		}

		// mark as in delivered and send notification to user via database
		if ($request->has('is_delivered') && $request->filled('is_delivered')) {

			if ($data['is_delivered'] == 1 && $order->is_delivered == 0) {

				$order->markAsApproved();
				$order->markAsDelivered()->notifyCustomerOrderIsDelivered();
				
				auth()->user()->markAsFinishTrip();
			} elseif ($data['is_delivered'] == 0 && $order->is_delivered == 1) {

				$order->markAsNotDelivered();
				auth()->user()->markAsOnTrip();
			}
		}

		if ($request->has('is_cancelled') && $request->filled('is_cancelled')) {

			if ($data['is_cancelled'] == 1 && $order->is_cancelled == 0) {

				$order->markAsCancelled()->notifyCustomerOrderIsCancelled();
			} elseif ($data['is_cancelled'] == 0 && $order->is_cancelled == 1) {

				$order->markAsNotCancelled();
			}
		}


		return $this->api()->response($order, trans('main.updated'));
	}
}
