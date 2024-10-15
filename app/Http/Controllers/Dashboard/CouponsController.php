<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use MixCode\Category;
use MixCode\Http\Controllers\Controller;
use MixCode\Coupon;
use MixCode\Http\Requests\CouponsRequest;
use MixCode\DataTables\CouponsDataTable;
use MixCode\DataTables\Trashed\CouponsTrashedDataTable;

class CouponsController extends Controller
{
    protected $viewPath = 'dashboard.coupons';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CouponsDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Coupon::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.coupons');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionName = trans('main.add') . ' ' . trans('main.coupons');
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
    public function store(CouponsRequest $request, Coupon $coupon)
    {    
       
        $coupon = $coupon->create($request->all());

        if ($request->has('images')) {
            $coupon->uploadMultiMediaFromRequest($request->images);
        }

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

 



    /**
     * Display the specified resource.
     *
     * @param  \MixCode\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    { 

        if (request()->wantsJson()) {
            return $coupon;
        }
 

        $sectionName = trans('main.show') . ' ' . Str::limit($coupon->name_by_lang, 30);
         $coupon->load([ 'media', 'creator' ]);
        
        return view("{$this->viewPath}.show", compact('sectionName', 'coupon'  ));
    }

 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        

        $sectionName = trans('main.edit') . ' ' . Str::limit($coupon->name_by_lang, 30);
        $user           = $coupon->creator;
   
        return view("{$this->viewPath}.edit", compact(
            'sectionName', 
            'coupon' 
            
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(CouponsRequest $request, Coupon $coupon)
    {
 
        $coupon->update($request->all());
        
        if ($request->has('images')) {
            $coupon->uploadMultiMediaFromRequest($request->images);
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $this->authorize('delete', $coupon);

        $coupon->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.coupons.index');
    }

    public function destroyGroup(Request $request)
    {
        $user = auth()->user();
 

        Coupon::destroy($request->selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.coupons.index');
    }

    // Soft Deletes Methods
    public function trashed(CouponsTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Coupon::where('creator_id', auth()->id())->get();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.coupons');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->find($id);

        $this->authorize('restore', $coupon);

        $coupon->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.coupons.trashed');
    }
    
    public function forceDelete($id)
    {
        $coupon = Coupon::onlyTrashed()->find($id);

        $this->authorize('forceDelete', $coupon);
        
        $coupon->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.coupons.trashed');
    }

    public function active(Coupon $coupon)
    {    
        $this->authorize('view', $coupon);

        $coupon->markAsActive() ;

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

    public function inActive(Coupon $coupon)
    {    
        $this->authorize('view', $coupon);

        $coupon->markAsInActive();

        // Make Coupon Pending if it is activated successfully
        if ($coupon->isInActive()) {
            $coupon->markAsPending();
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

 
    public function publish(Coupon $coupon)
    {    
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $coupon->markAsPublished() ;

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

    public function pending(Coupon $coupon)
    {    
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $coupon->markAsPending() ;

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.coupons.show', $coupon);
    }

    public function destroyMedia(Coupon $coupon, Request $request)
    {    
        $this->authorize('view', $coupon);

        if (! $coupon) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (! $request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
        $coupon->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }
}
