<?php

namespace MixCode\DataTables\Trashed;

use Yajra\DataTables\Services\DataTable;
use MixCode\Faq;
use MixCode\DataTables\BuilderParameters;

class FaqsTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('status', function (Faq $model) {
                $status = trans('main.active');
                $color = 'badge-success';
                
                if ($model->isInActive()) {
                    $status = trans('main.disabled');
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->addColumn('restore', 'dashboard.faqs.buttons.restore')
            ->addColumn('delete', 'dashboard.faqs.buttons.force_delete')
            ->rawColumns(['checkbox', 'status', 'restore', 'delete']);
    }

    public function query(Faq $model)
    {
        return $model->newQuery()->onlyTrashed()->select('*');
    }

    public function html()
    {
        $selectFields = [
            [
                'index_num' => 3,
                'selectValues' => [
                    Faq::ACTIVE_STATUS      => trans('main.active'),
                    Faq::INACTIVE_STATUS    => trans('main.disabled'),
                ]
            ],
        ];

        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getCustomBuilderParameters([1, 2],$selectFields));

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
                'name' => "en_question",
                'data'    => 'en_question',
                'title'   => trans('main.en_question'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "ar_question",
                'data'    => 'ar_question',
                'title'   => trans('main.ar_question'),
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
        return 'Trashed_Faq_' . date('YmdHis');
    }
}
