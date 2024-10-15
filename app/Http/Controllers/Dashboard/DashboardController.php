<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Categorization;
use MixCode\Order;
use MixCode\Category;
use MixCode\City;
use MixCode\Contact;
use MixCode\Country;
use MixCode\Discount;
use MixCode\Http\Controllers\Controller;
use MixCode\Product;
use MixCode\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isCompany()) {
            return $this->companiesDashboard();
        }


        if ($user->isAdmin()) {

            return $this->adminsDashboard();
        }
    }

    protected function adminsDashboard()
    {
    
        $admins_count                   = User::byCountry()->typeAdmin()->count();
        $customers_count                = User::byCountry()->typeCustomer()->count();
        $drivers_count                  = User::byCountry()->typeDriver()->count();
        $companies_count                = User::byCountry()->typeCompany()->count();
        $categorizations_count          = Categorization::count();
        $categories_count               = Category::count();
        $all_products_count             = Product::byCountry()->count();
        $admin_products_count           = Product::byCountry()->where('creator_id', auth()->id())->count();
        $other_products_count           = Product::byCountry()->where('creator_id', '!=', auth()->id())->count();
        $admin_published_products_count = Product::byCountry()->published()->where('creator_id', auth()->id())->count();
        $admin_pending_products_count   = Product::byCountry()->pending()->where('creator_id', auth()->id())->count();
        $other_published_products_count = Product::byCountry()->published()->where('creator_id', '!=', auth()->id())->count();
        $other_pending_products_count   = Product::byCountry()->pending()->where('creator_id', '!=', auth()->id())->count();
        $messages_count                 = Contact::byCountry()->count();
        $my_orders_count                = Order::byCountry()->whereHas('products', function ($q) {
            $q->where('creator_id', '=', auth()->id());
        })->count();
        $other_orders_count          = Order::byCountry()->whereHas('products', function ($q) {
            $q->where('creator_id', '!=', auth()->id());
        })->count();



        return view('dashboard.dashboard', compact(
            'admins_count',
            'customers_count',
            'drivers_count',
            'companies_count',
            'categorizations_count',
            'categories_count',
            'all_products_count',
            'admin_products_count',
            'other_products_count',
            'admin_published_products_count',
            'admin_pending_products_count',
            'other_published_products_count',
            'other_pending_products_count',
            'my_orders_count',
            'other_orders_count',
            'messages_count'
        ));
    }

    protected function companiesDashboard()
    {
        $customers_count                       = User::byCountry()->typeCustomer()->count();
        $drivers_count                         = User::byCountry()->typeDriver()->count();
        $company_products_count                = Product::byCountry()->where('creator_id', auth()->id())->count();
        $other_products_count                  = Product::byCountry()->where('creator_id', '!=', auth()->id())->count();
        $company_published_products_count      = Product::byCountry()->published()->where('creator_id', auth()->id())->count();
        $company_pending_products_count        = Product::byCountry()->pending()->where('creator_id', auth()->id())->count();
        $orders_count                           = Order::where('provider_id', auth()->id())->count();

        return view('dashboard.company_dashboard', compact(
            'customers_count',
            'drivers_count',
            'company_products_count',
            'other_products_count',
            'company_published_products_count',
            'company_pending_products_count',
            'orders_count'

        ));
    }
}
