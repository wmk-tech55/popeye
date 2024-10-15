<?php

namespace MixCode\Http\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use MixCode\OrderReport;

class CompanyOrderAccounttantReports extends Component
{

    use WithPagination;


    protected $paginationTheme = 'bootstrap';
    public    $limitPerPage    = 1;
    public $user;


    public function render()
    {

        $reports = OrderReport::where('company_id', $this->user->id)->paginate($this->limitPerPage);


        return view('livewire.dashboard.company-order-accounttant-reports', compact('reports'));
    }
}
