<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\Order;

class   OrdersDataTable extends DataTable
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
            ->addColumn('delete', 'dashboard.orders.buttons.delete')

            ->rawColumns(['checkbox',  'is_approved', 'is_delivered', 'show', 'delete']);
    }

    public function query(Order $model)
    {
        return Order::where('provider_id', auth()->id());
        // return $model->newQuery()
        //     ->whereHas('products', function ($q) {
        //         return  $q->where('creator_id', auth()->id());
        //     })
        //     ->byCountry()
        //     ->with('customer')
        //     ->select('orders.*');
    }

    public function html()
    {
        $selectFields = [


            [
                'index_num' => 4,
                'selectValues' => [
                    Order::APPROVED_STATUS     => trans('main.approved'),
                    Order::NOT_APPROVED_STATUS    => trans('main.not_approved'),
                ],
            ],
            [
                'index_num' => 5,
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
                'name'              => 'checkbox',
                'data'              => 'checkbox',
                'title'             => '<input type="checkbox" class="select-all" onclick="select_all()">',
                'orderable'         => false,
                'searchable'        => false,
                'exportable'        => false,
                'printable'         => false,
                'width'             => '10px',
                'aaSorting'         => 'none',
                'class'             => ['no-export'],
            ],
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
                'width'          => '150px',
            ],


            [
                'name' => "is_delivered",
                'data'    => 'is_delivered',
                'title'   => trans('main.delivery_status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '150px',
            ],

            [
                'name' => 'show',
                'data' => 'show',
                'title' => trans('main.show'),
                'class' => ['no-export'],
                'exportable' => false,
                'printable'  => false,
                'searchable' => false,
                'orderable'  => false,
            ],

            // [
            //     'name' => 'delete',
            //     'data' => 'delete',
            //     'title' => trans('main.delete'),
            //     'class' => ['no-export'],
            //     'exportable' => false,
            //     'printable'  => false,
            //     'searchable' => false,
            //     'orderable'  => false,
              
            // ],

        ];
    }


    protected function getButtons(): array
    {
        return [
            [
                'extend' => 'print',
                'className' => 'btn btn-dark',
                'text' => '<i class="fas fa-print"></i> ' . trans('main.print'),
                'exportOptions' => [
                    // Exclude No Exportable, Printable Fields
                    'columns' => ':not(.no-export)'
                ],
            ],
            [
                'extend' => 'excelHtml5',
                'className' => 'btn btn-success',
                'text' => '<i class="fas fa-file-excel"> </i> ' . trans('main.export_excel'),
                'exportOptions' => [
                    // Exclude No Exportable, Printable Fields
                    'columns' => ':not(.no-export)'
                ],
            ],
            // [
            //     'text' => '<i class="fas fa-trash"></i> ' . trans('main.delete'),
            //     'className' => 'btn btn-danger deleteBtn',
            // ],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Order_' . date('YmdHis');
    }
}
