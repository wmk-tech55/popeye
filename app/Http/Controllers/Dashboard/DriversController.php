<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MixCode\Http\Requests\DriversRequest;
use Illuminate\Support\Facades\Hash;
use MixCode\Country;
use MixCode\DataTables\DriversDataTable;
use MixCode\DataTables\Trashed\DriversTrashedDataTable;
use MixCode\Payment\Contracts\Driver;

class DriversController extends Controller
{
    protected $viewPath = 'dashboard.drivers';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(DriversDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::where('type', User::DRIVER_TYPE)->all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.drivers');

        return $dataTable->render("{$this->viewPath}.index", compact('sectionName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("{$this->viewPath}.create", [
            'sectionName' => trans('main.add') . ' ' . trans('main.drivers'),
            'type'        => User::DRIVER_TYPE,
            'userStatus'  => User::USER_STATUS,
            'countries'   => Country::active()->get(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DriversRequest $request, User $driver)
    {
             $data = $request->validated(); 
       $data['active_country_id'] = $data['country_data_id'];

             $driver              = $driver->register($data);


        if ($request->hasFile('vehicle_photos')) {

            $driver->uploadMultiMediaFromRequest('vehicle_photos');
        }

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.drivers.show', $driver);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(User $driver)
    {
        if (request()->wantsJson()) {
            return $driver;
        }

        $sectionName = trans('main.show') . ' ' . $driver->full_name;

        $driver->load('media');

        return view("{$this->viewPath}.show", ['sectionName' => $sectionName, 'driver' => $driver]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(User $driver)
    {
        $sectionName = trans('main.edit') . ' ' . $driver->full_name;

        return view("{$this->viewPath}.edit", [
            'sectionName'  => $sectionName,
            'driver'       => $driver,
            'type'         => User::DRIVER_TYPE,
            'driverStatus' => User::USER_STATUS,
            'countries'    => Country::active()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(DriversRequest $request, User $driver)
    {
              $data                = $request->validated() ;
        $data['active_country_id'] = $data['country_data_id'];
        $data['country_id']        = $data['country_data_id'];
 
        
        $driver->update($data);

    
        if ($request->has('vehicle_photos')) {

            $driver->uploadMultiMediaFromRequest('vehicle_photos');
        }

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.drivers.show', $driver);
    }

    /**
     * Update the specified resource password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $driver)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $driver->update(['password' => Hash::make($request->password)]);

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.drivers.show', $driver);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\User  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $driver)
    {
        abort_if($driver->id == auth()->id(), Response::HTTP_UNAUTHORIZED);

        $driver->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.drivers.index');
    }

    public function destroyGroup(Request $request)
    {
        $selected_data = $request->selected_data;

        abort_unless(!!$selected_data, Response::HTTP_NOT_FOUND);

        abort_if(in_array(auth()->id(), $selected_data), Response::HTTP_UNAUTHORIZED);

        User::destroy($selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.drivers.index');
    }


    public function forceDestroyGroup(Request $request)
    {
        User::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.drivers.trashed');
    }

    // Soft Deletes Methods

    public function trashed(DriversTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.drivers');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $drivers = User::onlyTrashed()->find($id);

        $drivers->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.drivers.trashed');
    }

    public function forceDelete($id)
    {
        $drivers = User::onlyTrashed()->find($id);

        $drivers->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.drivers.trashed');
    }


    public function destroyMedia($driver, Request $request)
    {
        $driver = User::findOrFail($driver);

        if (!$driver) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        if (!$request->has('media_id')) {
            return response()->json(['status' => false, 'message' => trans('main.not_found')]);
        }

        // Method Exists in HasMediaTrait
 
        $driver->deleteMedia($request->media_id);

        return response()->json(['status' => true, 'message' => trans('main.deleted-message')]);
    }


    
    public function active(User $driver)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $driver->markAsActive()
            ->notifyUserForActivatingAccount();


        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.drivers.show', $driver);
    }

    public function pending(User $driver)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $driver->markAsPending()
            ->notifyUserForMadeAccountPending();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.drivers.show', $driver);
    }
}
