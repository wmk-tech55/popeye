<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\Wallet;

class WalletsDataTable extends DataTable
{
    use BuilderParameters;

    public $id ;
 
    public function getData($id ){
        $this->id = $id ;
        return  $this ;
    }

    public function dataTable($query)
    {
        return datatables($query)
            ->editColumn('status', function (Wallet $model) {
                $status = trans('main.unpaid_balance');
                $color = 'badge-danger';
                
                if ($model->isPaid()) {
                    $status = trans('main.paid_balance');
                    $color = 'badge-success';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->editColumn('date', function (Wallet $model) {
               
                return $model->created_at->calendar() ;
            })
            ->rawColumns([ 'status', 'show','date' ]);
    }

    public function query(Wallet $model)
    {
        return $model->newQuery()
        ->when(!is_null($this->id) && $this->id != '' , function ($builder) {
            $builder->where('company_id',$this->id) ; 
        })
        ->with('order')
        ->select('*');
    }

    public function html()
    {

        $selectFields = [
            [
                'index_num' => 3,
                'selectValues' => [
                    Wallet::BALANCE_PAID        => trans('main.paid_balance'),
                    Wallet::BALANCE_NOT_PAID    => trans('main.unpaid_balance'),
                ]
            ],
        ];

        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getCustomBuilderParameters([0,1,2], $selectFields));

        if (isLang('ar')) {
            $html = $html->parameters($this->getCustomBuilderParameters([0,1,2], $selectFields, true));
        }

        return $html;
    }

    protected function getColumns()
    {
        return [
            
            [
                'name' => "order.invoice_id",
                'data'    => 'order.invoice_id',
                'title'   => trans('main.invoice_id'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
         
            [
                'name' => "balance",
                'data'    => 'balance',
                'title'   => trans('main.balance'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
         
            [
                'name' => "date",
                'data'    => 'date',
                'title'   => trans('main.date'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "status",
                'data'    => 'status',
                'title'   => trans('main.status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ] 
           
        ];
    }

    
    protected function getButtons() : array
    {
        return [];
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Wallet_' . date('YmdHis');
    }
}
