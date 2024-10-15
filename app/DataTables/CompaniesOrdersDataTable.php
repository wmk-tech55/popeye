<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\Order;

class CompaniesOrdersDataTable extends DataTable
{
    use BuilderParameters;

    protected $company;

    public function filterDataByCompany($company)
    {
        $this->company  = $company;

        return $this;
    }

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('customer_full_name', function (Order $model) {
                return $model->name;
            })
            ->editColumn('is_paid', function (Order $model) {
                $status = null;
                $color = null;

                if ($model->isPaid()) {
                    $status = trans("main.paid");
                    $color = 'badge-success';
                } else {
                    $status = trans("main.not_paid");
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
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
            ->rawColumns(['checkbox', 'is_paid', 'is_approved', 'is_delivered', 'show', 'delete']);
    }

    public function query(Order $model)
    {
        $query = $model->newQuery()
            ->when($this->company, function ($q) {
                // Apply filter only if $this->company is not null or empty
                $q->where('provider_id', $this->company);
            })
            ->when(request('is_paid'), function ($q) {
                $q->where('is_paid', request('is_paid'));
            })
            ->when(request('is_approved'), function ($q) {
                $q->where('is_approved', request('is_approved'));
            })
            ->when(request('is_delivered'), function ($q) {
                $q->where('is_delivered', request('is_delivered'));
            });

        return $query;
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
            ],
            [
                'index_num' => 4,
                'selectValues' => [
                    Order::PAID_STATUS        => trans('main.paid'),
                    Order::NOT_PAID_STATUS    => trans('main.not_paid'),
                ]
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
                'width'          => '140px',
            ],
            [
                'name' => "is_paid",
                'data'    => 'is_paid',
                'title'   => trans('main.paid_status'),
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
                'name' => 'show',
                'data' => 'show',
                'title' => trans('main.show'),
                'class' => ['no-export'],
                'exportable' => false,
                'printable'  => false,
                'searchable' => false,
                'orderable'  => false,
                'width'          => '50px',
            ],

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
