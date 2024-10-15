<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Categorization;
use MixCode\Classification;
use MixCode\Http\Requests\CategorizationsRequest;
use MixCode\DataTables\CategorizationsDataTable;
use MixCode\DataTables\Trashed\CategorizationsTrashedDataTable;

class CategorizationsController extends Controller
{
    protected $viewPath = 'dashboard.categorizations';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategorizationsDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Categorization::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.store_categorizations');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.store_categorizations');

        return view("{$this->viewPath}.create", compact('sectionName'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategorizationsRequest $request)
    {

        $categorization = Categorization::create($request->all());


        if ($request->hasFile('icon')) {
            $categorization->uploadSingleMediaFromRequest('icon', 'icon');
        }

        notify('success', trans('main.added-message'));

        //  return redirect()->route('dashboard.categorizations.show', $categorization);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function show(Categorization $categorization)
    {
        if (request()->wantsJson()) {
            return $categorization;
        }

        $sectionName = trans('main.show') . ' ' . $categorization->name_by_lang;

        return view("{$this->viewPath}.show", compact('sectionName', 'categorization'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function edit(Categorization $categorization)
    {
        $sectionName = trans('main.edit') . ' ' . $categorization->name_by_lang;

        return view("{$this->viewPath}.edit", compact('sectionName', 'categorization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function update(CategorizationsRequest $request, Categorization $categorization)
    {
        $categorization->update($request->all());

        if ($request->hasFile('icon')) {
            $categorization->updateSingleMediaFromRequest('icon', 'icon');
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.categorizations.show', $categorization);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Categorization  $categorization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorization $categorization)
    {
        $categorization->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categorizations.index');
    }

    public function destroyGroup(Request $request)
    {
        Categorization::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categorizations.index');
    }


    public function forceDestroyGroup(Request $request)
    {
        Categorization::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categorizations.trashed');
    }


    // Soft Deletes Methods
    public function trashed(CategorizationsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Categorization::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.store_categorizations');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $categorizations = Categorization::onlyTrashed()->find($id);

        $categorizations->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.categorizations.trashed');
    }

    public function forceDelete($id)
    {
        $categorizations = Categorization::onlyTrashed()->find($id);

        $categorizations->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.categorizations.trashed');
    }
}
