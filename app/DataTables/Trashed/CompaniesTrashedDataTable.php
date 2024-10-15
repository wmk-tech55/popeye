<?php

namespace MixCode\DataTables\Trashed;

use Yajra\DataTables\Services\DataTable;
use MixCode\User;
use MixCode\DataTables\BuilderParameters;

class CompaniesTrashedDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('type', function (User $model) {
                $type = null;
                $color = null;

                if ($model->isCustomer()) {
                    $type = trans("main.customer");
                    $color = 'badge-secondary';
                } else {
                    $type = trans("main.admin");
                    $color = 'badge-primary';
                }

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
            ->addColumn('restore', 'dashboard.companies.buttons.restore')
            ->addColumn('delete', 'dashboard.companies.buttons.force_delete')
            ->rawColumns(['checkbox', 'type', 'status', 'restore', 'delete']);
    }

    public function query(User $model)
    {
        return $model->newQuery()->typeCompany()->onlyTrashed()->select('*');
    }

    public function html()
    {
        $selectFields = [

            [
                'index_num' => 4,
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
            ->parameters($this->getCustomBuilderParameters([1, 2, 3,4], $selectFields));

        if (isLang('ar')) {
            $html = $html->parameters($this->getCustomBuilderParameters([1, 2, 3,4], $selectFields, true));
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
                'width'          => '200px',
            ],
            [
                'name' => "username",
                'data'    => 'username',
                'title'   => trans('site.company_name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "email",
                'data'    => 'email',
                'title'   => trans('main.email'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name' => "phone",
                'data'    => 'phone',
                'title'   => trans('main.phone'),
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
        return 'Trashed_Company_' . date('YmdHis');
    }
}
