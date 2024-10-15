<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\Discount;
use MixCode\Classification;
use Illuminate\Support\Facades\DB;
use MixCode\Color;
use MixCode\Http\Requests\DiscountsRequest;
use MixCode\DataTables\DiscountsDataTable;
use MixCode\DataTables\Trashed\DiscountsTrashedDataTable;
use MixCode\Product;

class DiscountsController extends Controller
{
    protected $viewPath = 'dashboard.discounts';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DiscountsDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Discount::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.discounts');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.discounts');
        $user = auth()->user();

        $categories      = Category::where('parent_id', '=', NULL)->active()->get();

        $products     = Product::get();
        return view("{$this->viewPath}.create", compact(
            'sectionName',
            'products',
            'categories'

        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountsRequest $request, Discount $discount)
    {

        $discount = $discount->createNewDiscount($request);


        if ($request->has('products_id')) {

            $fileds = Product::whereIn('id', $request->products_id)->update(['discount_id' => $discount->id]);
            $fileds = Product::update(['discount_id' => $discount->id]);
        } elseif ($request->has('categories_id')) {

            $categories_id = $request->categories_id;

            $fileds = Product::whereHas('categories', function ($query) use ($categories_id) {
                $query->whereIn('category_id', $categories_id);
            })->update(['discount_id' => $discount->id]);
        } elseif ($request->has('category_parent_id')) {

            $child_categories = Category::where('parent_id', $request->category_parent_id)->get();

            foreach ($child_categories as $category) {
                $categories[] = $category->id;
            }

            $fileds = Product::whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            })->update(['discount_id' => $discount->id]);
        }


        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        // Give Admin Access To View Discount Details
        // if (!auth()->user()->isAdmin()) {
        //     $this->authorize('view', $discount);
        // }

        if (request()->wantsJson()) {
            return $discount;
        }

        $sectionName = trans('main.show') . ' ' . Str::limit($discount->name_by_lang, 30);

        $discount->load([  'creator', 'products']);

        return view("{$this->viewPath}.show", compact('sectionName', 'discount'));
    }



    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getProductsByCategoryId(Request $request)
    {
        $id = $request->category_id;
        $products = Product::whereHas('categories', function ($query) use ($id) {
            $query->where('category_id', $id);
        })->get();

        return  view("{$this->viewPath}.includes._all_products", compact('products'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {


        $sectionName = trans('main.edit') . ' ' . Str::limit($discount->name_by_lang, 30);
        $user           = $discount->creator;

        $categories          = Category::where('parent_id', NULL)->active()->get();
        $subCategories       = Category::where('parent_id', '!=', NULL)->active()->get();
        $products            = Product::get();

        $discount->load('products');
        return view("{$this->viewPath}.edit", compact(
            'sectionName',
            'discount',
            'categories',
            'products',
            'subCategories'

        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountsRequest $request, Discount $discount)
    {

        $discount = $discount->updateDiscount($request);

        foreach ($request->products_id as $product_id) {

            $fileds = Product::where('id', $product_id)->update(['discount_id' => $discount->id]);
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        //  $this->authorize('delete', $discount);

        $discount->delete();

        Product::where('discount_id', $discount->id)->update(['discount_id' => null]);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.discounts.index');
    }

    public function destroyGroup(Request $request)
    {
         
        Discount::destroy($request->selected_data);
 
      Product::whereIn('discount_id', $request->selected_data)->update(['discount_id' => null]);
        

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.discounts.index');
    }

    // Soft Deletes Methods
    public function trashed(DiscountsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Discount::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.discounts');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $discount = Discount::onlyTrashed()->find($id);

        //   $this->authorize('restore', $discount);

        $discount->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.discounts.trashed');
    }

    public function forceDelete($id)
    {
        $discount = Discount::onlyTrashed()->find($id);

        //    $this->authorize('forceDelete', $discount);

        $discount->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.discounts.trashed');
    }

    public function forceDestroyGroup(Request $request)
    {
        Discount::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.discounts.trashed');
    }

    public function active(Discount $discount)
    {
        //  $this->authorize('view', $discount);

        $discount->markAsActive() ; //->notifyAdminsForOrderActivation();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    public function inActive(Discount $discount)
    {
        //  $this->authorize('view', $discount);

        $discount->markAsInActive();

        // Make Discount Pending if it is activated successfully
        if ($discount->isInActive()) {
            $discount->markAsPending();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    public function publish(Discount $discount)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $discount->markAsPublished();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    public function pending(Discount $discount)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $discount->markAsPending();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.discounts.show', $discount);
    }

    public function destroyMedia(Discount $discount, Request $request)
    {
        //    $this->authorize('view', $discount);

        if (!$discount) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
        $discount->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }
}
