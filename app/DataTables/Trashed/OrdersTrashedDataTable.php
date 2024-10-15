<?php

namespace MixCode\DataTables\Trashed;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Order;
use MixCode\DataTables\BuilderParameters;

class OrdersTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
        ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
        ->editColumn('customer_full_name', function (Order $model) {
            return $model->name;
        })
        
        ->editColumn('is_approved', function (Order $model) {
            $status = null;
            $color = null;

            if ($model->isApproved()) {
                $status = trans("main.approved");
                $color = 'badge-success';
            } else {
                $status = trans("main.not_approved");
                $color = 'badge-warning';
            }

            return "<span class='badge custom-badge {$color}'>{$status}</span>";
        })

        ->editColumn('is_delivered', function (Order $model) {
            $status = null;
            $color = null;

            if ($model->isDelivered()) {
                $status = trans("main.delivered");
                $color = 'badge-success';
            } else {
                $status = trans("main.not_delivered");
                $color = 'badge-warning';
            }

            return "<span class='badge custom-badge {$color}'>{$status}</span>";
        })

        ->addColumn('show', 'dashboard.orders.buttons.show')
        ->addColumn('delete', 'dashboard.orders.buttons.force_delete')
        ->addColumn('restore', 'dashboard.orders.buttons.restore')
        ->rawColumns(['checkbox',   'is_approved' ,'is_delivered','restore', 'delete']);
            
    }

    public function query(Order $model)
    {
 
        return $model->newQuery()
             ->with('customer')
             ->onlyTrashed()
             ->select('orders.*');
    }

    public function html()
    {
        $selectFields = [
           
           
            [
                'index_num' => 3,
                'selectValues' => [
                    Order::APPROVED_STATUS     => trans('main.approved'),
                    Order::NOT_APPROVED_STATUS    => trans('main.not_approved'),
                ],
            ]
,
            [
                    'index_num' => 4,
                    'selectValues' => [
                        Order::DELIVERED_STATUS     => trans('main.delivered'),
                        Order::NOT_DELIVERED_STATUS    => trans('main.not_delivered'),
                    ]

             ],
        ];

        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getCustomBuilderParameters([1, 2], $selectFields));

        if (isLang('ar')) {
            $html = $html->parameters($this->getCustomBuilderParameters([1, 2], $selectFields, true));
        }

        return $html;
    }

    protected function getColumns()
    {
        return [
            [
                'name' => "invoice_id",
                'data'    => 'invoice_id',
                'title'   => trans('main.invoice_id'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],

            [
                'name' => "full_name",
                'data'    => 'full_name',
                'title'   => trans('main.customer'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "total",
                'data'    => 'total',
                'title'   => trans('site.total'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '50px',
            ],
            [
                'name' => "is_approved",
                'data'    => 'is_approved',
                'title'   => trans('main.approval_status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],
             

            [
                'name' => "is_delivered",
                'data'    => 'is_delivered',
                'title'   => trans('main.delivery_status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],
             
            [
                'name' => 'restore',
                'data' => 'restore',
                'title' => trans('main.restore'),
                'class' => ['no-export'],
                'exportable' => false,
                'printable'  => false,
                'searchable' => false,
                'orderable'  => false,
            ],
            [
                'name' => 'delete',
                'data' => 'delete',
                'title' => trans('main.delete'),
                'class' => ['no-export'],
                'exportable' => false,
                'printable'  => false,
                'searchable' => false,
                'orderable'  => false,
            ],
        ];
    }

    protected function getButtons() : array
    {
        return [
            [
                'text' => '<i class="fas fa-trash"></i> ' . trans('main.delete'),
                'className' => 'btn btn-danger deleteBtn',
            ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Trashed_Order_' . date('YmdHis');
    }
}
