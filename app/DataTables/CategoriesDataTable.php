<?php

namespace MixCode\DataTables;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Category;

class CategoriesDataTable extends DataTable
{
    use BuilderParameters; 
    
    protected $parent_id ;
    
    public function setId($parent_id){ 
       
         $this->parent_id = $parent_id;
         return $this ;
    }


    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('en_name', function (Model $category) { return $category->en_name; })
            ->editColumn('ar_name', function (Model $category) { return $category->ar_name; })
            
            ->editColumn('status', function (Category $model) {
                $status = trans('main.active');
                $color = 'badge-success';
                
                if ($model->isInActive()) {
                    $status = trans('main.disabled');
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
            ->addColumn('show', 'dashboard.categories.buttons.show')
            ->addColumn('show_subCategories','dashboard.categories.buttons.show_subCategories')
            ->addColumn('edit', 'dashboard.categories.buttons.edit')
            ->addColumn('delete', 'dashboard.categories.buttons.delete')
            ->rawColumns(['checkbox', 'status', 'show', 'edit', 'delete','show_subCategories']);
    }




    public function query(Category $model)
    {
        return $model->newQuery()
        ->with('children')
        ->where('parent_id', '=',$this->parent_id)
        ->select('*');
    }

    public function html()
    {

        $selectFields = [
            [
                'index_num' => 3,
                'selectValues' => [
                    Category::ACTIVE_STATUS      => trans('main.active'),
                    Category::INACTIVE_STATUS    => trans('main.disabled'),
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
                'name' => "status",
                'data'    => 'status',
                'title'   => trans('main.status'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '100px',
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
                'name' => 'show_subCategories',
                'data' => 'show_subCategories',
                'title' => trans('main.sub_categories'),
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
        return 'Category_' . date('YmdHis');
    }
}
