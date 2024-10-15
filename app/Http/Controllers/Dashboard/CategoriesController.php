<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use MixCode\Categorization;
use MixCode\Http\Controllers\Controller;
use MixCode\Category;
use MixCode\Http\Requests\CategoriesRequest;
use MixCode\DataTables\CategoriesDataTable;
use MixCode\DataTables\SubCategoriesDataTable;
use MixCode\DataTables\Trashed\CategoriesTrashedDataTable;

class CategoriesController extends Controller
{
    protected $viewPath = 'dashboard.categories';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriesDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Category::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.categories');

        $parent_id = NULL;
        return $dataTable->setId($parent_id)->render("{$this->viewPath}.index", compact('sectionName'));
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryByParentId(SubCategoriesDataTable $dataTable, $parent_id)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Category::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.sub_categories');


        return $dataTable->setId($parent_id)->render("{$this->viewPath}.includes.index", compact('sectionName'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getByCategory(Request $request)
    {


        $categories = Category::where('parent_id', '=', $request->category_id)->get();


        return view("{$this->viewPath}.ajax-request-fileds.getByCategory", compact('categories'));
    }


    /**
     * Store a newly quickS tore resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quickStore(Request $request)
    {
        $data  = $request->all();

        if ($request->parent_id == 'null') {

            unset($data['parent_id']);
        }

        $category = Category::create($data);


        $category->uploadSingleMediaFromRequest('icon', 'icon');


        $all_categories          =  Category::active();
        $categories['data']      =  $all_categories->isParent()->get();
        $categories['model_data'] = $all_categories->get();

        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName     = trans('main.add') . ' ' . trans('main.categories');
        $categories      = Category::where('parent_id', NULL)->active()->get();
        $categorizations = Categorization::get();
        return view("{$this->viewPath}.create", compact('sectionName', 'categories', 'categorizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoriesRequest $request)
    {

        $category = Category::create($request->all());

        if ($request->hasFile('icon')) {
            $category->uploadSingleMediaFromRequest('icon', 'icon');
        }

        Cache()->forget('categories');

        // Artisan::call('cache:clear');

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.categories.show', $category);
    }


    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function getByCategorization(Request $request)
    {
        
        $categories = Category::query()
            ->where('categorization_id', $request->categorization_id)
            ->active()
            ->get();

       
        return view("{$this->viewPath}.includes._categories_by_categorizations", compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function getCategoriesByParentId(Request $request)
    {
        $subCategories = Category::query()
            ->when(is_array($request->category_parent_id), function (Builder $builder) use ($request) {
                return $builder->whereIn('parent_id', $request->category_parent_id);
            })
            ->when(is_string($request->category_parent_id), function (Builder $builder) use ($request) {
                return $builder->where('parent_id', $request->category_parent_id);
            })
            ->active()
            ->get();

        return view("{$this->viewPath}.includes._categories_items_info", compact('subCategories'));
    }



    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if (request()->wantsJson()) {
            return $category;
        }

        $sectionName = trans('main.show') . ' ' . $category->name_by_lang;

        return view("{$this->viewPath}.show", compact('sectionName', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $sectionName     = trans('main.edit') . ' ' . $category->name_by_lang;
        $categories      = Category::where('parent_id', NULL)->active()->get();
        $categorizations = Categorization::get();
        $category->load('parent');

        return view("{$this->viewPath}.edit", compact('sectionName', 'category', 'categories', 'categorizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoriesRequest $request, Category $category)
    {
        $category->update($request->all());

        if ($request->hasFile('icon')) {
            $category->updateSingleMediaFromRequest('icon', 'icon');
        }

        Cache()->forget('categories');

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.categories.show', $category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        $category_children    = $category->children()->onlyTrashed()->pluck('id')->toArray();

        $categories        =   Category::whereIn('id', $category_children)->onlyTrashed()->get();

        foreach ($categories as $category) {

            $category->delete();
        }

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categories.index');
    }

    public function destroyGroup(Request $request)
    {
        Category::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categories.index');
    }

    // Soft Deletes Methods
    public function trashed(CategoriesTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Category::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.categories');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);

        $category->restore();

        $category_children    = $category->children()->onlyTrashed()->pluck('id')->toArray();

        $categories        =   Category::whereIn('id', $category_children)->onlyTrashed()->get();

        foreach ($categories as $category) {

            $category->restore();
        }


        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.categories.trashed');
    }

    public function forceDelete($id)
    {
        $categories = Category::onlyTrashed()->find($id);

        $categories->forceDelete();

        $category_children    = $categories->children()->onlyTrashed()->pluck('id')->toArray();

        $categories        =   Category::whereIn('id', $category_children)->onlyTrashed()->get();

        foreach ($categories as $category) {

            $category->forceDelete();
        }


        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categories.trashed');
    }



    public function forceDestroyGroup(Request $request)
    {
        Category::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categories.trashed');
    }
}
