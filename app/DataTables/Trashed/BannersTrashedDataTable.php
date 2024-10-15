<?php

namespace MixCode\DataTables\Trashed;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Banner;
use MixCode\DataTables\BuilderParameters;

class BannersTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('en_name', function (Model $banner) { return $banner->en_name; })
            ->editColumn('ar_name', function (Model $banner) { return $banner->ar_name; })
            ->editColumn('position', function (Banner $model) {
               
                return trans("main.".$model->position);
                 
             })
            ->addColumn('restore', 'dashboard.banners.buttons.restore')
            ->addColumn('delete', 'dashboard.banners.buttons.force_delete')
            ->rawColumns(['checkbox', 'status', 'position','restore', 'delete']);
    }

    public function query(Banner $model)
    {
        return $model->newQuery()->onlyTrashed()->select('*');
    }

    public function html()
    {
        $selectFields = [
            // [
            //     'index_num' => 3,
            //     'selectValues' => [
            //         Banner::ACTIVE_STATUS      => trans('main.published'),
            //         Banner::INACTIVE_STATUS    => trans('main.pending'),
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
                'title'   => trans('main.en_title'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "ar_name",
                'data'    => 'ar_name',
                'title'   => trans('main.ar_title'),
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
        return 'Trashed_Banner_' . date('YmdHis');
    }
}
