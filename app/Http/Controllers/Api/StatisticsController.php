<?php

namespace MixCode\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Order;
use MixCode\Feature;
use MixCode\Http\Controllers\Controller;
use MixCode\Language;
use MixCode\Product;
use MoaAlaa\ApiResponder\ApiResponder;

class StatisticsController extends Controller
{
    use ApiResponder;

    public function statistics(Request $request)
    {
        $date =  Carbon::today();

        if ($request->has('date') && $request->filled('date')) {
            $date = $request->date;
        }

        $all_products_count    = Product::where('creator_id', auth()->id())->count();
        $orders_count          = Order::notDeleted()->whereHas('products', function ($q) {
            $q->where('creator_id', '=', auth()->id());
        })->count();
        $complete_orders_count = Order::notDeleted()->delivered()->whereHas('products', function ($q) {
            $q->where('creator_id', '=', auth()->id());
        })->count();

        $total_profit_for_to_day = Order::notDeleted()->delivered()->whereDate('created_at', $date)->sum('total');

        /*         $company_published_products_count = Product::published()->where('creator_id', auth()->id())->count();
        $company_pending_products_count   = Product::pending()->where('creator_id', auth()->id())->count();
        $paid_orders_count                     = Order::paid()->whereHas('products', function ($q) {
            $q->where('creator_id', '=', auth()->id());
        })->count();

        $not_paid_orders_count                     = Order::notPaid()->whereHas('products', function ($q) {
            $q->where('creator_id', '=', auth()->id());
        })->count();

        $active_orders_count                     = Order::query()
            ->approved()
            ->notDelivered()
            ->whereHas('products', function ($q) {
                $q->where('creator_id', '=', auth()->id());
            })->count();

        $pending_orders_count                     = Order::query()
            ->notApproved()
            ->whereHas('products', function ($q) {
                $q->where('creator_id', '=', auth()->id());
            })->count(); */


        $data = [
            'all_products_count'                     => $all_products_count,
            'all_orders_count'                       => $orders_count,
            'complete_orders_count'                  => $complete_orders_count,
            'total_profit_for_delivered_orders_only' => $total_profit_for_to_day
            /*            'published_products_count' => $company_published_products_count,
            'pending_products_count'   => $company_pending_products_count,
            'paid_orders_count'        => $paid_orders_count,
            'not_paid_orders_count'    => $not_paid_orders_count,
            'active_orders_count'      => $active_orders_count,
            'pending_orders_count'     => $pending_orders_count, */


        ];

        return $this->api()->response($data);
    }
}
