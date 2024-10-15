<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MixCode\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Hash;
use MixCode\Categorization;
use MixCode\City;
use MixCode\Country;
use MixCode\DataTables\UsersDataTable;
use MixCode\DataTables\Trashed\UsersTrashedDataTable;
use MixCode\Http\Requests\CompanyProfileRequest;

class UsersController extends Controller
{
    protected $viewPath = 'dashboard.users';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.users');

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
            'sectionName' => trans('main.add') . ' ' . trans('main.users'),
            'userTypes'   => [User::ADMIN_TYPE, User::CUSTOMER_TYPE],
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
    public function store(UsersRequest $request, User $user)
    {

        $user = $user->register($request->validated());

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (request()->wantsJson()) {
            return $user;
        }

        $sectionName = trans('main.show') . ' ' . $user->full_name;

        $user->load(['media','userCountry']);

        return view("{$this->viewPath}.show", ['sectionName' => $sectionName, 'user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {

        $sectionName = trans('main.edit') . ' ' . $user->full_name;

        return view("{$this->viewPath}.edit", [
            'sectionName' => $sectionName,
            'user'        => $user,
            'userTypes'   => [User::ADMIN_TYPE, User::CUSTOMER_TYPE],
            'userStatus'  => User::USER_STATUS,
            'countries'   => Country::active()->get(),
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = auth()->user();

        $sectionName     = trans('main.edit') . ' ' . $user->full_name;

        return view("{$this->viewPath}.edit_profile", [
            'sectionName'     => $sectionName,
            'company'         => $user,
            'categorizations' => Categorization::get(),
            'cities'          => City::get(),
            'countries'       => Country::active()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UsersRequest $request, User $user)
    {
        
        $user->updateWithMedia($request->validated());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.users.show', $user);
    }
    /**
     * Update the Company Profile data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function CompanyProfile(CompanyProfileRequest $request)
    {
        $user = auth()->user();
        $user->updateWithMedia($request->validated());

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.users.company.edit.profile');
    }

    /**
     * Update the specified resource password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        notify('success', trans('main.updated'));

        // return redirect()->route('dashboard.users.show', $user);
        return redirect()->back();
    }


    public function changeCountry($country)
    {

        auth()->user()->changeCountryForUser($country);

        return redirect()->route('dashboard.dashboard');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        abort_if($user->id == auth()->id(), Response::HTTP_UNAUTHORIZED);

        $user->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.users.index');
    }

    public function destroyGroup(Request $request)
    {
        $selected_data = $request->selected_data;

        abort_unless(!!$selected_data, Response::HTTP_NOT_FOUND);

        abort_if(in_array(auth()->id(), $selected_data), Response::HTTP_UNAUTHORIZED);

        User::destroy($selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.users.index');
    }

    // Soft Deletes Methods 

    public function trashed(UsersTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.users');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $users = User::onlyTrashed()->find($id);

        $users->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.users.trashed');
    }

    public function forceDelete($id)
    {
        $users = User::onlyTrashed()->find($id);

        $users->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.users.trashed');
    }


    public function forceDestroyGroup(Request $request)
    {
        User::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.users.trashed');
    }
}
