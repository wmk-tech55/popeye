<?php

namespace MixCode\DataTables;

use Yajra\DataTables\Services\DataTable;
use MixCode\User;

class UsersDataTable extends DataTable
{
    use BuilderParameters;

    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('type', function (User $model) {
                $type  = null;
                $color = null;
                $icon  = null;

                if ($model->isCustomer()) {
                    $type = trans("main.customer");
                    $color = 'badge-secondary';
                    $icon = '<i class="fas fa-user"></i>';
                } elseif ($model->isCompany()) {
                    $type = trans("main.company");
                    $color = 'badge-success';
                    $icon = '<i class="fas fa-building"></i>';
                } elseif ($model->isDriver()) {
                    $type = trans("main.driver");
                    $color = 'badge-info';
                    $icon = '<i class="fas fa-shipping-fast"></i>';
                } else {
                    $type = trans("main.admin");
                    $color = 'badge-primary';
                    $icon = '<i class="fas fa-user-cog"></i>';
                }

                return "<span class='badge custom-badge {$color}'>{$icon} {$type}</span>";
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
            ->addColumn('show', 'dashboard.users.buttons.show')
            ->addColumn('edit', 'dashboard.users.buttons.edit')
            ->addColumn('delete', 'dashboard.users.buttons.delete')
            ->rawColumns(['checkbox', 'type', 'status', 'show', 'edit', 'delete']);
    }

    public function query(User $model)
    {
        return $model->newQuery()
            ->where('type', '!=', User::COMPANY_TYPE)
            ->byCountry()
            ->select('*');
    }

    public function html()
    {
        $selectFields = [
            [
                'index_num' => 4,
                'selectValues' => [
                    User::CUSTOMER_TYPE => trans('main.customers'),
                    User::ADMIN_TYPE => trans('main.admins'),

                ]
            ],
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
            ->parameters($this->getCustomBuilderParameters([1, 2, 3], $selectFields));

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
                'name' => "full_name",
                'data'    => 'full_name',
                'title'   => trans('main.full_name'),
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
                'name' => "type",
                'data'    => 'type',
                'title'   => trans('main.type'),
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
