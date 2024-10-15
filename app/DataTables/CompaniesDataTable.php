<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\User;

class CompaniesDataTable extends DataTable
{
    use BuilderParameters;


    protected $categorizations;

    public function filterData($categorizations)
    {
        $this->categorizations  = $categorizations;

        return $this;
    }

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('type', function (User $model) {


                $type = trans("main.driver");
                $color = 'badge-info';

                return "<span class='badge custom-badge {$color}'>{$type}</span>";
            })
            ->editColumn('status', function (User $model) {
                $status = null;
                $color = null;

                if ($model->isActive()) {
                    $status = trans("main.active");
                    $color = 'badge-success';
                } elseif ($model->isPending()) {
                    $status = trans("main.pending");
                    $color = 'badge-warning';
                } else {
                    $status = trans("main.disabled");
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->editColumn('categorization', function (User $model) {

                $name = getLanguage() . '_name';
                return  $model->categorization ? $model->categorization->$name : trans("main.not_found");
            })
            ->addColumn('show', 'dashboard.companies.buttons.show')
            ->addColumn('edit', 'dashboard.companies.buttons.edit')
            ->addColumn('delete', 'dashboard.companies.buttons.delete')
            ->rawColumns(['checkbox', 'type', 'categorization','status', 'show', 'edit', 'delete']);
    }

    public function query(User $model)
    {
        return $model->newQuery()
            ->typeCompany()
            ->byCountry()
            ->when(count($this->categorizations) > 0, function ($q) {
                $q->whereIn('categorization_id', $this->categorizations);
            })
            ->select('*');
    }

    public function html()
    {
        $selectFields = [

            [
                'index_num' => 5,
                'selectValues' => [
                    User::ACTIVE_STATUS     => trans('main.active'),
                    User::PENDING_STATUS    => trans('main.pending'),
                    User::INACTIVE_STATUS   => trans('main.disabled'),
                ]
            ]
        ];

        $html = $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters($this->getCustomBuilderParameters([1, 2, 3, 4], $selectFields));

        if (isLang('ar')) {
            $html = $html->parameters($this->getCustomBuilderParameters([1, 2, 3, 4], $selectFields, true));
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
                'name' => "full_name",
                'data'    => 'full_name',
                'title'   => trans('main.full_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],
            [
                'name' => "username",
                'data'    => 'username',
                'title'   => trans('site.company_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],
            [
                'name' => "email",
                'data'    => 'email',
                'title'   => trans('main.email'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],
            [
                'name' => "phone",
                'data'    => 'phone',
                'title'   => trans('main.phone'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
            ],

            [
                'name' => "status",
                'data'    => 'status',
                'title'   => trans('main.status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '95px',
            ],
            [
                'name' => "categorization",
                'data'    => 'categorization',
                'title'   => trans('main.categorization'),
                'searchable' => false,
                'orderable'  => false,
                'width'          => '50px',
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
        return 'User_' . date('YmdHis');
    }
}
