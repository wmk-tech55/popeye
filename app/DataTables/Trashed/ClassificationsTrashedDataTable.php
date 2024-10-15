<?php

namespace MixCode\DataTables\Trashed;

use Yajra\DataTables\Services\DataTable;
use MixCode\Classification;
use MixCode\DataTables\BuilderParameters;

class ClassificationsTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('status', function (Classification $model) {
                $status = trans('main.active');
                $classification = 'badge-success';
                
                if ($model->isInActive()) {
                    $status = trans('main.disabled');
                    $classification = 'badge-danger';
                }

                return "<span class='badge custom-badge {$classification}'>{$status}</span>";
            })
            ->addColumn('restore', 'dashboard.classifications.buttons.restore')
            ->addColumn('delete', 'dashboard.classifications.buttons.force_delete')
            ->rawColumns(['checkbox', 'status', 'restore', 'delete']);
    }

    public function query(Classification $model)
    {
        return $model->newQuery()->onlyTrashed()->select('*');
    }

    public function html()
    {
        $selectFields = [
            // [
            //     'index_num' => 4,
            //     'selectValues' => [
            //         Classification::ACTIVE_STATUS      => trans('main.active'),
            //         Classification::INACTIVE_STATUS    => trans('main.disabled'),
            //     ]
            // ],
        ];

        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getCustomBuilderParameters([1, 2, 3],$selectFields));

        if (isLang('ar')) {
            $html = $html->parameters($this->getCustomBuilderParameters([1, 2, 3], $selectFields, true));
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
                'name' => "en_name",
                'data'    => 'en_name',
                'title'   => trans('main.en_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "ar_name",
                'data'    => 'ar_name',
                'title'   => trans('main.ar_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
          
            [
                'name' => "ar_value",
                'data'    => 'ar_value',
                'title'   => trans('main.ar_value'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "en_value",
                'data'    => 'en_value',
                'title'   => trans('main.en_value'),
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
        return 'Trashed_Classification_' . date('YmdHis');
    }
}
