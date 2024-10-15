<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Http\Controllers\Controller;
use MixCode\PromoCode;
use MixCode\Http\Requests\PromoCodesRequest;
use MixCode\DataTables\PromoCodesDataTable;
use MixCode\DataTables\Trashed\PromoCodesTrashedDataTable;
use MixCode\Product;

class PromoCodesController extends Controller
{
    protected $viewPath = 'dashboard.promoCodes';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PromoCodesDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return PromoCode::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.promoCodes');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.promoCodes');
        $user = auth()->user();

        return view("{$this->viewPath}.create", compact(
            'sectionName'

        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromoCodesRequest $request, PromoCode $promoCode)
    {

        $data = [];
        $data = $request->all();
        $randomCode = Str::random(10);
        $data['code'] = "PC-" . $randomCode;
 
        $promoCode = $promoCode->create($data);

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.promoCodes.show', $promoCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function show(PromoCode $promoCode)
    {
        // Give Admin Access To View PromoCode Details
        if (!auth()->user()->isAdmin()) {
            $this->authorize('view', $promoCode);
        }

        if (request()->wantsJson()) {
            return $promoCode;
        }

        $sectionName = trans('main.show') . ' ' . Str::limit($promoCode->code, 30);

        $promoCode->load(['creator']);

        return view("{$this->viewPath}.show", compact('sectionName', 'promoCode'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function edit(PromoCode $promoCode)
    {
        $sectionName  = trans('main.edit') . ' ' . Str::limit($promoCode->code, 30);

        $user         = $promoCode->creator;

        return view("{$this->viewPath}.edit", compact(
            'sectionName',
            'promoCode'

        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function update(PromoCodesRequest $request, PromoCode $promoCode)
    {

        $promoCode->update($request->all());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.promoCodes.show', $promoCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\PromoCode  $promoCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromoCode $promoCode)
    {

        $promoCode->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.promoCodes.index');
    }

    public function destroyGroup(Request $request)
    {



        PromoCode::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.promoCodes.index');
    }

    // Soft Deletes Methods
    public function trashed(PromoCodesTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return PromoCode::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.promoCodes');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $promoCode = PromoCode::onlyTrashed()->find($id);

        $this->authorize('restore', $promoCode);

        $promoCode->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.promoCodes.trashed');
    }

    public function forceDelete($id) 
    {
        $promoCode = PromoCode::onlyTrashed()->find($id);

        if (!auth()->user()->isAdmin()) {
            $this->authorize('view', $promoCode);
        }


        $promoCode->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.promoCodes.trashed');
    }

    public function active(PromoCode $promoCode)
    {
        if (!auth()->user()->isAdmin()) {
            $this->authorize('view', $promoCode);
        }


        $promoCode->markAsActive();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.promoCodes.show', $promoCode);
    }

    public function inActive(PromoCode $promoCode)
    {
        if (!auth()->user()->isAdmin()) {
            $this->authorize('view', $promoCode);
        }


        $promoCode->markAsInActive();

        // Make PromoCode disable if it is activated successfully
        if ($promoCode->active()) {
            $promoCode->markAsInActive();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.promoCodes.show', $promoCode);
    }


    public function forceDestroyGroup(Request $request)
    {
        PromoCode::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.promoCodes.trashed');
    }


    public function destroyMedia(PromoCode $promoCode, Request $request)
    {
        $this->authorize('view', $promoCode);

        if (!$promoCode) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
        $promoCode->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }


    
}
