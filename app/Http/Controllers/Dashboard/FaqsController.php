<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Faq;
use MixCode\Http\Requests\FaqsRequest;
use MixCode\DataTables\FaqsDataTable;
use MixCode\DataTables\Trashed\FaqsTrashedDataTable;

class FaqsController extends Controller
{
    protected $viewPath = 'dashboard.faqs';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FaqsDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Faq::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.faqs');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.faqs');

        return view("{$this->viewPath}.create", compact('sectionName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqsRequest $request)
    {
        $faq = Faq::create($request->all());

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.faqs.show', $faq);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        if (request()->wantsJson()) {
            return $faq;
        }

        $sectionName = trans('main.show') . ' ' . $faq->question_by_lang;

        return view("{$this->viewPath}.show", compact('sectionName', 'faq'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        $sectionName = trans('main.edit') . ' ' . $faq->question_by_lang;
        
        return view("{$this->viewPath}.edit", compact('sectionName', 'faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(FaqsRequest $request, Faq $faq)
    {
        $faq->update($request->all());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.faqs.show', $faq);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.faqs.index');
    }

    public function destroyGroup(Request $request)
    {
        Faq::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.faqs.index');
    }

    // Soft Deletes Methods
    public function trashed(FaqsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Faq::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.faqs');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $faqs = Faq::onlyTrashed()->find($id);

        $faqs->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.faqs.trashed');
    }
    
    public function forceDelete($id)
    {
        $faqs = Faq::onlyTrashed()->find($id);

        $faqs->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.faqs.trashed');
    }

    public function forceDestroyGroup(Request $request)
    {
        Faq::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.faqs.trashed');
    }

    public function active(Faq $faq)
    {        
        $faq->markAsActive();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.faqs.show', $faq);
    }

    public function inActive(Faq $faq)
    {
        $faq->markAsInActive();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.faqs.show', $faq);
    }
}
