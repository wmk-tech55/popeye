<?php

namespace MixCode\Http\Controllers\Dashboard;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\User;
use MixCode\Http\Requests\CompaniesRequest;
use Illuminate\Support\Facades\Hash;
use MixCode\Categorization;
use MixCode\City;
use MixCode\Country;
use MixCode\DataTables\CompaniesDataTable;
use MixCode\DataTables\Trashed\CompaniesTrashedDataTable;
use MixCode\Http\Requests\WorkTimesRequest;
use MixCode\Wallet;
use MixCode\WorkTime;

class CompaniesController extends Controller
{
    protected $viewPath = 'dashboard.companies';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CompaniesDataTable $dataTable, Request $request)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::where('type', User::COMPANY_TYPE)->all();
        }

        $sectionName = trans('main.show_all') . ' ' . trans('main.companies');

        $listCategorizations = Categorization::all();

        $categorizations = [];

        if ($request->has('categorizations') && $request->categorizations != '') {

            $categorizations = $request->categorizations;
        }

        return $dataTable->filterData($categorizations)->render("{$this->viewPath}.index", compact('sectionName', 'listCategorizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      
        return view("{$this->viewPath}.create", [
            'sectionName'     => trans('main.add') . ' ' . trans('main.companies'),
            'type'            => User::COMPANY_TYPE,
            'categorizations' => Categorization::get(),
            'userStatus'      => User::USER_STATUS,
            'cities'          => City::get(),
            'countries'       => Country::active()->get(),
            'work_times'      => WorkTime::defaultWorkTimes(),
            'countries'       => Country::active()->get(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompaniesRequest $request, User $company)
    {
        $data                = $request->validated() ;
        $data['active_country_id'] = $data['country_id'];

        $company        = $company->register($data);

        // $company->categorizations()->attach($request->categorizations_id);

        notify('success', trans('main.added-message'));

        return redirect()->route('dashboard.companies.show', $company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \MixCode\User  $company
     * @return \Illuminate\Http\Response
     */
    public function show(User $company)
    {
        if (request()->wantsJson()) {
            return $company;
        }

        $sectionName = trans('main.show') . ' ' . $company->full_name;

        $company->load(['media', 'reports','country','categorization']);

        $unpaidBalances = Wallet::where('company_id', $company->id)->unPaid()->take(6)->get();
        $paidBalances   = Wallet::where('company_id', $company->id)->paid()->take(6)->get();
        $totalOrders    = Wallet::where('company_id', $company->id)->count();

        return view("{$this->viewPath}.show", [
            'sectionName'    => $sectionName,
            'company'        => $company,
            'unpaidBalances' => $unpaidBalances,
            'paidBalances'   => $paidBalances,
            'totalOrders'    => $totalOrders
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MixCode\User  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(User $company)
    {
        $sectionName = trans('main.edit') . ' ' . $company->full_name;


        return view("{$this->viewPath}.edit", [
            'sectionName'     => $sectionName,
            'company'         => $company,
            'type'            => User::COMPANY_TYPE,
            'categorizations' => Categorization::get(),
            'companyStatus'   => User::USER_STATUS,
            'cities'          => City::get(),
            'countries'       => Country::active()->get(),
            'work_times'      => WorkTime::defaultWorkTimes(),
            'countries'       => Country::active()->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompaniesRequest $request, User $company)
    {


        $company->updateWithMedia($request->validated());


        // $company->categorizations()->sync($request->categorizations_id);

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.companies.show', $company);
    }

    /**
     * Update the specified resource password in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MixCode\User  $company
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $company)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $company->update(['password' => Hash::make($request->password)]);

        notify('success', trans('main.updated'));

        // return redirect()->route('dashboard.companies.show', $company);
        return redirect()->back();
    }


    /**
     * Update work time.
     *
     * @param  \App\User  $User
     * @param  \App\WorkTime  $work_time
     * @param  \App\Http\Requests\WorkTimesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateWorkTime(User $company, WorkTime $work_time, WorkTimesRequest $request)
    {
        $data = $request->only(['day_is_vacation', 'open_time', 'close_time']);
        $data['day_is_vacation'] = $data['day_is_vacation'] === 'yes';

        $work_time->update($data);

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.companies.show', $company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MixCode\User  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $company)
    {
        abort_if($company->id == auth()->id(), Response::HTTP_UNAUTHORIZED);

        $company->delete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.companies.index');
    }

    public function destroyGroup(Request $request)
    {
        $selected_data = $request->selected_data;

        abort_unless(!!$selected_data, Response::HTTP_NOT_FOUND);

        abort_if(in_array(auth()->id(), $selected_data), Response::HTTP_UNAUTHORIZED);

        User::destroy($selected_data);

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.companies.index');
    }

    // Soft Deletes Methods

    public function trashed(CompaniesTrashedDataTable $dataTable)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return User::all();
        }

        $sectionName = trans('main.show_all') . ' '  . trans('main.trashed') . ' ' . trans('main.companies');

        return $dataTable->render("{$this->viewPath}.trashed", compact('sectionName'));
    }

    public function restore($id)
    {
        $companies = User::onlyTrashed()->find($id);

        $companies->restore();

        notify('success', trans('main.restored'));

        return redirect()->route('dashboard.companies.trashed');
    }

    public function forceDelete($id)
    {
        $companies = User::onlyTrashed()->find($id);

        $companies->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.companies.trashed');
    }



    public function forceDestroyGroup(Request $request)
    {
        User::onlyTrashed()->whereIn('id', $request->selected_data)->forceDelete();

        notify('success', trans('main.deleted-message'));

        return redirect()->route('dashboard.companies.trashed');
    }



    public function active(User $company)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $company->markAsActive()
            ->notifyUserForActivatingAccount();


        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.companies.show', $company);
    }

    public function pending(User $company)
    {
        abort_unless(auth()->user()->isAdmin(), Response::HTTP_UNAUTHORIZED);

        $company->markAsPending()
            ->notifyUserForMadeAccountPending();

        notify('success', trans('main.updated'));

        return redirect()->route('dashboard.companies.show', $company);
    }
}
