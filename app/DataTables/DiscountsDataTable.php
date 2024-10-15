<?php

namespace MixCode\DataTables;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Discount;

class DiscountsDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', function (Discount $discount) {
                
                $checkbox = '<i class="fas fa-window-close"></i>';
                
                if (auth()->id() === $discount->creator_id) {
                    $checkbox = "<input type='checkbox' class='selected_data' name='selected_data[]' value='{$discount->id}'>";
                }

                return $checkbox;
            })
            ->editColumn('en_name', function (Model $discount) { return $discount->en_name; })
            ->editColumn('ar_name', function (Model $discount) { return $discount->ar_name; })
            
            ->editColumn('status', function (Discount $model) {
                $status = trans('main.active');
                $color = 'badge-success';
                
                if ($model->isPending()) {
                    $status = trans('main.pending');
                    $color = 'badge-warning';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->addColumn('show', 'dashboard.discounts.buttons.show')
            ->addColumn('edit', 'dashboard.discounts.buttons.edit')
            ->addColumn('delete', 'dashboard.discounts.buttons.delete')
            ->rawColumns(['checkbox',  'status', 'show', 'edit', 'delete']);
    }

    public function query(Discount $model)
    {
        if (auth()->user()->isAdmin()) {
            return $model->newQuery()->select('*');
        }
        
        return $model->newQuery()->where('creator_id', auth()->id())->select('*');
    }

    public function html()
    {
        $selectFields = [
            [
                'index_num' => 3,
                'selectValues' => [
                    Discount::ACTIVE_STATUS      => trans('main.active'),
                    Discount::INACTIVE_STATUS    => trans('main.disabled'),
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
                'name' => 'en_name',
                'data'    => 'en_name',
                'title'   => trans('main.en_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => 'ar_name',
                'data'    => 'ar_name',
                'title'   => trans('main.ar_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            
            [
                'name' => 'status',
                'data'    => 'status',
                'title'   => trans('main.status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
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
            [
                'name' => 'edit',
                'data' => 'edit',
                'title' => trans('main.edit'),
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

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Discount_' . date('YmdHis');
    }
}
