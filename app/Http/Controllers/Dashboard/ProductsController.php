<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Categorization;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\Product;
use MixCode\Classification;
use MixCode\Http\Requests\ProductsRequest;
use MixCode\DataTables\ProductsDataTable;
use MixCode\DataTables\Trashed\ProductsTrashedDataTable;
use MixCode\Discount;
use MixCode\ProductAddition;
use MixCode\ProductVariation;
use MixCode\User;

class ProductsController extends Controller
{
    protected $viewPath = 'dashboard.products';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable, Request $request)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Product::where('creator_id', auth()->id())->get();
        }

        $pageTitle           = trans('main.show_all') . ' ' . trans('main.products');
        $sectionName         = trans('main.show_all') . ' ' . trans('main.products');
        $categorizations     = [];
        $user                = '';
        $company             = '' ;
        $listCategorizations = Categorization::get();

        if ($request->has('categorizations') && $request->categorizations != '') {

            $categorizations = $request->categorizations;
        }

        if ($request->has('user') && $request->user != '') {

            $user        = $request->user;
            $company     = User::findOrFail($user);
            $pageTitle   = trans('main.show_all')  . ' ' . trans('main.products') . ' : ' . $company->full_name . ' ( ' . $company->username . ' ) ';
            $sectionName = trans('main.show_all') . ' ' . trans('main.products');
        }

        return $dataTable->filterData($categorizations)
            ->filterDataByCompany($user)
            ->render("{$this->viewPath}.index", compact('user','pageTitle', 'sectionName' ,'listCategorizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.products');

        //    $categories      = Category::get();
        $categorizations = Categorization::get();
        $groupTypes      = Product::GROUP_TYPES;

        return view("{$this->viewPath}.create", compact(
            'sectionName',
            /* 'categories', */
            'groupTypes',
            'categorizations'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductsRequest $request, Product $product)
    {
        $product = $product->createNewProduct($request);

        $product->notifySubscribersForNewProducts();


        if ($request->has('images')) {

            $product->uploadMultiMediaFromRequest('images');
        }


        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.products.show', $product);
    }



    /**
     * Store new product with new variation a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*  public function addNewProductVariation(Request $request,  $id)
    {
        $product = Product::find($id);

        $product->attachVariations($request);

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.products.show', $product);
    } */

    /**
     * Store new product with new additions a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*   public function addNewProductAddition(Request $request,  $id)
    {
        $product = Product::find($id);

        $request->request->add(['has_additions' => 'yes']);

        $product->attachAdditions($request);

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.products.show', $product);
    } */



    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Give Admin Access To View Product Details
        if (!auth()->user()->isAdmin()) {
            // $this->authorize('view', $product);
        }

        if (request()->wantsJson()) {
            return $product;
        }

        $categorizations = Categorization::all();
        $classifications = Classification::where('categorization_id', $product->categorization_id)->get();

        $sectionName = trans('main.show') . ' ' . Str::limit($product->name_by_lang, 30);

        $product->load(['media', 'creator',  'productGroupsWithAdditions']);


        return view("{$this->viewPath}.show", compact('sectionName', 'product',  'classifications', 'categorizations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        // $this->authorize('view', $product);

        $sectionName     = trans('main.edit') . ' ' . Str::limit($product->name_by_lang, 30);

        $product->load(['productVariations', 'productAdditions']);

        $categorizations = Categorization::get();
        $categories      = Category::where('categorization_id', $product->categorization_id)->get();
        $groupTypes      = Product::GROUP_TYPES;

        return view("{$this->viewPath}.edit", compact(
            'sectionName',
            'product',
            'groupTypes',
            'categories',
            'categorizations'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function additionGroupRow(Request $request)
    {
        $number   = $request->number  + 2;

        $groupTypes =  Product::GROUP_TYPES;

        return view("{$this->viewPath}.includes.ajax.addition-group-with-options", compact(
            'groupTypes',
            'number'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function additionRow(Request $request)
    {
        $number   = $request->number  + 2;
        $index   = $request->index;

        return view("{$this->viewPath}.includes.ajax.addition-options", compact('number', 'index'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductsRequest $request, Product $product)
    {

        $product          = $product->updateProduct($request);

        if ($request->has('images')) {

            $product->uploadMultiMediaFromRequest('images');
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $product);
    }


    /**
     * update   product with new variation  .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*  public function updateNewProductVariation(Request $request,  $id)
    {
        $productVariation = ProductVariation::find($id);

        $productVariation->update($request->all());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $productVariation->product_id);
    } */

    /**
     * update product with new additions 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*   public function updateNewProductAddition(Request $request,  $id)
    {
        $productAddition = ProductAddition::find($id);

        $productAddition->update($request->all());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $productAddition->product_id);
    }
 */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // $this->authorize('delete', $product);

        $product->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.products.index');
    }

    public function destroyGroup(Request $request)
    {
        $user = auth()->user();

        if ($user->isCompany()) {
            Product::select('creator_id')
                ->whereIn('id', $request->selected_data)
                ->get()
                ->pluck('creator_id')
                ->map(function ($product_creator_id) use ($user) {
                    abort_unless($product_creator_id === $user->id, Response::HTTP_FORBIDDEN);
                });
        }

        Product::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.products.index');
    }

    // Soft Deletes Methods
    public function trashed(ProductsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Product::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.products');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->find($id);

        // $this->authorize('restore', $product);

        $product->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.products.trashed');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->find($id);

        // $this->authorize('forceDelete', $product);

        $product->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.products.trashed');
    }

    public function forceDestroyGroup(Request $request)
    {
        Product::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.products.trashed');
    }


    public function active(Product $product)
    {
        // $this->authorize('view', $product);

        $product->markAsActive();

        if (auth()->user()->isCompany()) {
            // $product->notifyAdminsForOrderActivation();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $product);
    }

    public function inActive(Product $product)
    {
        // $this->authorize('view', $product);

        $product->markAsInActive();

        // Make Product Pending if it is activated successfully
        if ($product->isInActive()) {
            $product->markAsPending();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $product);
    }

    public function publish(Product $product)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $product->markAsPublished(); //->notifyUserForPublishProduct();

        /*  if (auth()->user()->isCompany()) {
            $product->notifyUserForPublishProduct();
        } */

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $product);
    }

    public function pending(Product $product)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $product->markAsPending(); //->notifyUserForPendingProduct();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.products.show', $product);
    }

    public function destroyMedia(Product $product, Request $request)
    {
        // $this->authorize('view', $product);

        if (!$product) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
        $product->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }
}
