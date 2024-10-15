<?php

namespace MixCode\DataTables\Trashed;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Product;
use MixCode\DataTables\BuilderParameters;

class ProductsTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('en_name', function (Model $product) {
                return $product->en_name;
            })
            ->editColumn('ar_name', function (Model $product) {
                return $product->ar_name;
            })
            ->editColumn('status', function (Product $model) {
                $status = trans('main.publish');
                $color = 'badge-success';

                if ($model->isPending()) {
                    $status = trans('main.pending');
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->addColumn('restore', 'dashboard.products.buttons.restore')
            ->addColumn('delete', 'dashboard.products.buttons.force_delete')
            ->rawColumns(['checkbox', 'status', 'restore', 'delete']);
    }

    public function query(Product $model)
    {
        return $model->newQuery()->onlyTrashed()->select('*');
    }

    public function html()
    {
        $selectFields = [
            [
                'index_num' => 3,
                'selectValues' => [
                    Product::ACTIVE_STATUS      => trans('main.publish'),
                    Product::INACTIVE_STATUS    => trans('main.pending'),
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
                'name'       => "ar_name",
                'data'       => 'ar_name',
                'title'      => trans('main.name'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '200px',
            ],
            [
                'name'       => "price",
                'data'       => 'price',
                'title'      => trans('main.price'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '200px',
            ],
            [
                'name'       => "discount_percentage",
                'data'       => 'discount_percentage',
                'title'      => trans('main.discount_percentage'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '100px',
            ],
            [
                'name'       => "price_after_discount",
                'data'       => 'price_after_discount',
                'title'      => trans('main.price_after_discount'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '100px',
            ],
            [
                'name' => "status",
                'data'    => 'status',
                'title'   => trans('main.status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
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

    protected function getButtons(): array
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
        return 'Trashed_Product_' . date('YmdHis');
    }
}
