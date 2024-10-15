<?php

namespace MixCode\Http\Controllers\Dashboard;

use GuzzleHttp\Promise\Each;
use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\Wallet;
use MixCode\Http\Requests\WalletsRequest;
use MixCode\DataTables\WalletsDataTable;
use MixCode\DataTables\Trashed\WalletsTrashedDataTable;
use MixCode\User;

class WalletsController extends Controller
{
    protected $viewPath = 'dashboard.wallets';

    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WalletsDataTable $dataTable, Request $request)
    {
        if (app()->environment('testing') && request()->wantsJson()) {
            return Wallet::all();
        }
       
        $company = User::select('full_name')->find($request->company );
        
        $name     = $company->full_name ;

         if ($request->has('company') && $request->company != '') {

              $id  =  $request->company;
        }else{
            notify('error', trans('main.no_data_for_this_user'));
            return back();
        }

        
        $sectionName = trans('main.show') . ' ' . trans('main.wallet');

        return $dataTable->getData($id)->render("{$this->viewPath}.index", compact('sectionName','name'));
    }


    

 	/**
	 * Accept Order Request By the company  
	 *
	 * @param  \MixCode\Wallet  $order
	 * @return \Illuminate\Http\Response
	 */
	public function payTheDue( $company, Wallet $wallet)
	{
   
         $userBalances  = Wallet::where('company_id',$company)->unPaid()->get();

         $userBalances->each(function ($userBalance)  {
              $userBalance->update(['status'=> Wallet::BALANCE_PAID]);
         });
 
          
         notify('success', trans('main.balance_paid_successfully'));
         return back();

	}
 



}
