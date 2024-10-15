<?php

namespace MixCode\DataTables;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\PromoCode;

class PromoCodesDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', function (PromoCode $promoCode) {
                
                $checkbox = '<i class="fas fa-window-close"></i>';
                
                if (auth()->id() === $promoCode->creator_id) {
                    $checkbox = "<input type='checkbox' class='selected_data' name='selected_data[]' value='{$promoCode->id}'>";
                }

                return $checkbox;
            })
              
            ->editColumn('status', function (PromoCode $model) {
                $status = trans('main.active');
                $color = 'badge-success';
                
                if ($model->inActive()) {
                    $status = trans('main.disabled');
                    $color = 'badge-warning';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->addColumn('show', 'dashboard.promoCodes.buttons.show')
            ->addColumn('edit', 'dashboard.promoCodes.buttons.edit')
            ->addColumn('delete', 'dashboard.promoCodes.buttons.delete')
            ->rawColumns(['checkbox',  'status', 'show', 'edit', 'delete']);
    }

    public function query(PromoCode $model)
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
                    PromoCode::ACTIVE_STATUS      => trans('main.active'),
                    PromoCode::INACTIVE_STATUS    => trans('main.disabled'),
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
                'name' => 'code',
                'data'    => 'code',
                'title'   => trans('main.code'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => 'discount',
                'data'    => 'discount',
                'title'   => trans('main.discount'),
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
        return 'PromoCode_' . date('YmdHis');
    }
}
