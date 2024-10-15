<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Categorization;
use MixCode\Http\Controllers\Controller;
use MixCode\Classification;
use MixCode\Http\Requests\ClassificationsRequest;
use MixCode\DataTables\ClassificationsDataTable;
use MixCode\DataTables\Trashed\ClassificationsTrashedDataTable;

class ClassificationsController extends Controller
{
    protected $viewPath = 'dashboard.classifications';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClassificationsDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Classification::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.classifications');

        $categorizations   = Categorization::get();

        $categorization_id = '';

        if (request()->has('categorization_id') &&  request()->categorization_id != '') {

            $categorization_id =  request()->categorization_id;
        }


        return $dataTable->filterData($categorization_id)->render("{$this->viewPath}.index", compact('sectionName', 'categorizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.classifications');

        $categorizations = Categorization::get();
        return view("{$this->viewPath}.create", compact('sectionName', 'categorizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassificationsRequest $request)
    {

        $classification = Classification::create($request->all());

        notify('success', trans('main.added-message'));

        //   return redirect()->route('dashboard.classifications.show', $classification);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function show(Classification $classification)
    {
        if (request()->wantsJson()) {
            return $classification;
        }

        $sectionName = trans('main.show') . ' ' . $classification->name_by_lang;

        return view("{$this->viewPath}.show", compact('sectionName', 'classification'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Categorization  $Categorization
     * @return \Illuminate\Http\Response
     */

    public function getByCategorization(Request $request)
    {
        $classifications = [];


        $Categorization = Categorization::with('classifications')->find($request->categorization_id);

        if (!!$Categorization) {
            $classifications = $Categorization->classifications;
        }


        return view("{$this->viewPath}.ajax-request-fileds.getByCategorization", compact('classifications'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function edit(Classification $classification)
    {
        $sectionName = trans('main.edit') . ' ' . $classification->name_by_lang;
        $categorizations = Categorization::get();
        return view("{$this->viewPath}.edit", compact('sectionName', 'classification', 'categorizations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function update(ClassificationsRequest $request, Classification $classification)
    {
        $classification->update($request->all());


        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.classifications.show', $classification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Classification  $classification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classification $classification)
    {
        $classification->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.classifications.index');
    }

    public function destroyGroup(Request $request)
    {
        Classification::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.classifications.index');
    }

    // Soft Deletes Methods
    public function trashed(ClassificationsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Classification::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.classifications');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $classifications = Classification::onlyTrashed()->find($id);

        $classifications->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.classifications.trashed');
    }

    public function forceDelete($id)
    {
        $classifications = Classification::onlyTrashed()->find($id);

        $classifications->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.classifications.trashed');
    }



    public function forceDestroyGroup(Request $request)
    {
        Classification::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.classifications.trashed');
    }
}
