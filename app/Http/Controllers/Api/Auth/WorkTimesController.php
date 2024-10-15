<?php

namespace MixCode\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use MixCode\Http\Controllers\Controller;
use MixCode\WorkTime;
use MoaAlaa\ApiResponder\ApiResponder;

class WorkTimesController extends Controller
{
    use ApiResponder;

    public function getDefaultTimes()
    {
        $workTimes = WorkTime::defaultWorkTimes();

        return $this->api()->response($workTimes);
    }

    public function setWorkingTimes(Request $request)
    {
        WorkTime::attacheWorkingTimes($request);

        $workTimes = auth()->user()->work_times;

        return $this->api()->response($workTimes, trans('main.updated'), Response::HTTP_OK);
    }
}
