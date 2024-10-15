<?php

namespace MixCode\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use MixCode\Http\Controllers\Controller;
use MixCode\OrderReport;
use MixCode\User;

class OrderReportsController extends Controller
{


    /**
     * update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $reports = OrderReport::where('company_id', $id)->notPaid()->get();

        foreach ($reports as $report) {
            $report->order()->update(['is_paid' => 1]);
        }
        $reports->each(function ($report) {
            $report->update(['status' => OrderReport::PAID_STATUS]);
        });

        notify('success', trans('main.successfully_paid'));

        return redirect()->back();
    }
}
