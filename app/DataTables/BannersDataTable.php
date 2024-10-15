<?php

namespace MixCode\DataTables;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Banner;

class BannersDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('en_name', function (Model $banner) {
                return $banner->en_name;
            })
            ->editColumn('ar_name', function (Model $banner) {
                return $banner->ar_name;
            })
            ->editColumn('status', function (Banner $model) {
                $status = trans('main.active');
                $banner = 'badge-success';

                return "<span class='badge custom-badge {$banner}'>{$status}</span>";
            })

            ->editColumn('position', function (Banner $model) {

                return trans("main." . $model->position);
            })

            ->addColumn('show', 'dashboard.banners.buttons.show')
            ->addColumn('edit', 'dashboard.banners.buttons.edit')
            ->addColumn('delete', 'dashboard.banners.buttons.delete')
            ->rawColumns(['checkbox', 'status', 'position', 'show', 'edit', 'delete']);
    }

    public function query(Banner $model)
    {
        return $model->newQuery()->byCountry()->select('*');
    }

    public function html()
    {

        $selectFields = [
            // [
            //     'index_num' => 4,
            //     'selectValues' => [
            //         Banner::ACTIVE_STATUS      => trans('main.active'),
            //         Banner::INACTIVE_STATUS    => trans('main.disabled'),
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
                'class' => ['no-export'],
                'exportable' => false,
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "ar_name",
                'data'    => 'ar_name',
                'title'   => trans('main.ar_title'),
                'class' => ['no-export'],
                'exportable' => false,
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
        return 'Banner_' . date('YmdHis');
    }
}
