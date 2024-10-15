<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\Classification;

class ClassificationsDataTable extends DataTable
{
    use BuilderParameters;

    protected $categorization_id;

    public function filterData($categorization_id)
    {
        $this->categorization_id  = $categorization_id;

        return $this;
    }


    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('status', function (Classification $model) {
                $status = trans('main.active');
                $classification = 'badge-success';



                return "<span class='badge custom-badge {$classification}'>{$status}</span>";
            })
            ->addColumn('categorization', function (Classification $model) {

                if (isRtl()) {
                    $categorization =  $model->categorization->ar_name;
                } else {
                    $categorization =  $model->categorization->en_name;
                }
                return $categorization;
            })
            ->addColumn('show', 'dashboard.classifications.buttons.show')
            ->addColumn('edit', 'dashboard.classifications.buttons.edit')
            ->addColumn('delete', 'dashboard.classifications.buttons.delete')
            ->rawColumns(['checkbox', 'categorization', 'status', 'show', 'edit', 'delete']);
    }

    public function query(Classification $model)
    {
        return $model->newQuery()
            ->with(['categorization'])
            ->when($this->categorization_id != '', function ($builder) {
                $builder->where('classifications.categorization_id', $this->categorization_id);
            })
            ->select('classifications.*');
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
                'name' => "categorization",
                'data'    => 'categorization',
                'title'   => trans('main.categorization'),
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
        return 'Classification_' . date('YmdHis');
    }
}
