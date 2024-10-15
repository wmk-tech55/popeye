<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Order;
use MixCode\User;
use MixCode\Http\Controllers\Controller;
use MixCode\DataTables\OrdersDataTable;
use MixCode\DataTables\Trashed\OrdersTrashedDataTable;
use MixCode\DataTables\CompaniesOrdersDataTable;
use MixCode\Notifications\OrderActivated;
use MixCode\Notifications\OrderIsPreparing;
use MixCode\Notifications\OrderInShipping;
use MixCode\Notifications\OrderIsDelivered;

class OrderController extends Controller
{
    protected $viewPath = 'dashboard.orders';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrdersDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Order::where('product_creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.my_orders');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companiesOrders(CompaniesOrdersDataTable $dataTable, Request $request)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Order::where('product_creator_id', auth()->id())->get();
        }


        $pageTitle           = trans('main.show_all') . ' ' . trans('main.companies_orders');
        $sectionName         = trans('main.show_all') . ' ' . trans('main.companies_orders');
        $categorizations     = [];
        $user                = '';
        $company             = '';

        if ($request->has('user') && $request->user != '') {

            $user        = $request->user;
            $company     = User::findOrFail($user);
            $pageTitle   = trans('main.show_all')  . ' ' . trans('main.orders') . ' : ' . $company->full_name . ' ( ' . $company->username . ' ) ';
            $sectionName = trans('main.show_all') . ' ' . trans('main.orders');
        }


         if ($user) {
        return $dataTable->filterDataByCompany($user)->render("{$this->viewPath}.companies_orders", compact('user', 'pageTitle', 'sectionName'));
    }

    // Return all orders if no user is provided
    return $dataTable->render("{$this->viewPath}.companies_orders", compact('pageTitle', 'sectionName'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //  $this->authorize('view', $order);

        if (request()->wantsJson()) {
            return $order;
        }

        $drivers = User::where('type', User::DRIVER_TYPE)->get();

        $order->load(['products', 'customer']);

        // Check if customer exists
        if ($order->customer) {
            $sectionName = trans('main.show') . ' ' . $order->customer->full_name;
        } else {
            $sectionName = trans('main.show') . ' ' . trans('main.customer_not_found'); // Handle the case where customer is not found
        }


        return view("{$this->viewPath}.show", compact('sectionName', 'order', 'drivers'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        $data = [];
        $data = $request->all();


        // mark as approved and send notification to user via database
        if ($request->has('is_approved')  && $data['is_approved'] == 1 && $order->is_approved == 0) {

            //   $order->notifyCustomerOrderActivated();
        }
        // mark as preparing and send notification to user via database
        if ($request->has('is_preparing') && $data['is_preparing'] == 1 && $order->is_preparing == 0) {

            //  $order->notifyCustomerOrderIsPreparing();
        }

        // mark as in shipping and send notification to user via database
        if ($request->has('in_shipping') && $data['in_shipping'] == 1 && $order->in_shipping == 0) {

            //     $order->notifyCustomerOrderInShipping();
        }

        // mark as in delivered and send notification to user via database
        if ($request->has('is_delivered') && $data['is_delivered'] == 1 && $order->is_delivered == 0) {

            //    $order->notifyCustomerOrderIsDelivered();
        }


        $order->update($data);

        if ($order->isDelivered() &&  $order->driver) {

            $order->driver->markAsFinishTrip();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.orders.show', $order);
    }


    /**
     * Print Order Details.
     *
     * @param  \MixCode\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function print(Order $order)
    {
        $sectionName = trans('main.invoice_id') . ' ' . $order->invoice_id;

        $order->load(['products', 'customer', 'driver']);

        // Generate QRCode
        // $qrCode = new BarCode($order->invoice_id, 2, 35, 20);

        return view('dashboard.orders.print', compact('sectionName', 'order'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function assignDriver(Request $request, Order $order)
    {
        $order->update($request->all());

        $order->load('driver');

        //  $order->notifyDriverForNewOrder();


        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.orders.show', $order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.orders.index');
    }

    public function destroyGroup(Request $request)
    {

        Order::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.orders.index');
    }

    public function forceDestroyGroup(Request $request)
    {
        Order::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.orders.trashed');
    }


    public function trashed(OrdersTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Order::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.orders');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $orders = Order::onlyTrashed()->find($id);

        $orders->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.orders.trashed');
    }

    public function forceDelete($id)
    {
        $orders = Order::onlyTrashed()->find($id);

        $orders->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.orders.trashed');
    }
}
