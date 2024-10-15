<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Banner;
use MixCode\Http\Requests\BannersRequest;
use MixCode\DataTables\BannersDataTable;
use MixCode\DataTables\Trashed\BannersTrashedDataTable;
use MixCode\User;

class BannersController extends Controller
{
    protected $viewPath = 'dashboard.banners';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BannersDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Banner::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.banners');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.banners');

        $banner_positions = Banner::WEBSITE_SECTIONS;
        $countries = Banner::WEBSITE_SECTIONS;

        $stores = User::query()->typeCompany()->active()->byCountry()->get();
            
        return view("{$this->viewPath}.create", compact('sectionName', 'banner_positions', 'stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannersRequest $request)
    {
        $data              = $request->all();
        $data['data_country_id'] = auth()->user()->active_country_id;
        $banner            = Banner::create($data);

        if ($request->has('image')) {
            // First param is Request Files, second param postfix key
            $banner->uploadSingleMediaFromRequest('image');
        }

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.banners.show', $banner);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        if (request()->wantsJson()) {
            return $banner;
        }

        $sectionName = trans('main.show') . ' ' . $banner->name_by_lang;

        $banner->load('store');

        return view("{$this->viewPath}.show", compact('sectionName', 'banner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {

        $sectionName = trans('main.edit') . ' ' . $banner->name_by_lang;

        $banner_positions = Banner::WEBSITE_SECTIONS;

        $stores = User::query()->typeCompany()->active()->byCountry()->get();

        return view("{$this->viewPath}.edit", compact('sectionName', 'banner', 'banner_positions', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(BannersRequest $request, Banner $banner)
    {
        $banner->update($request->all());


        if ($request->has('image')) {
            // First param is Request Files, second param postfix key
            $banner->updateSingleMediaFromRequest('image');
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.banners.show', $banner);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {

        $banner->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.banners.index');
    }

    public function destroyGroup(Request $request)
    {

        Banner::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.banners.index');
    }

    // Soft Deletes Methods
    public function trashed(BannersTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Banner::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.banners');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->find($id);

        //  $this->authorize('restore', $banner);

        $banner->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.banners.trashed');
    }

    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->find($id);

        if (!auth()->user()->isAdmin()) {
            $this->authorize('view', $banner);
        }


        $banner->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.banners.trashed');
    }


    public function forceDestroyGroup(Request $request)
    {
        Banner::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.banners.trashed');
    }

    public function destroyMedia(Banner $banner, Request $request)
    {

        if (!$banner) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
        $banner->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }
}
