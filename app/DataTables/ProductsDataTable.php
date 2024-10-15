<?php

namespace MixCode\DataTables;

use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;
use MixCode\Product;

class ProductsDataTable extends DataTable
{
    use BuilderParameters;


    protected $categorizations;
    protected $company;

    public function filterData($categorizations)
    {
        $this->categorizations  = $categorizations;

        return $this;
    }

    public function filterDataByCompany($company)
    {
        $this->company  = $company;

        return $this;
    }


    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', '<input type="checkbox" class="selected_data" name="selected_data[]" value="{{ $id }}">')
            ->editColumn('en_name', function (Model $product) {
                return $product->en_name;
            })
            ->editColumn('ar_name', function (Model $product) {
                return $product->ar_name;
            })

            ->editColumn('status', function (Product $model) {
                $status = trans('main.active');
                $color = 'badge-success';

                if ($model->isPending()) {
                    $status = trans('main.pending');
                    $color = 'badge-danger';
                }

                return "<span class='badge custom-badge {$color}'>{$status}</span>";
            })
          /*   ->editColumn('categorization', function (Product $model) {
                $name = getLanguage() . '_name';
                return  $model->categorization ? $model->categorization->$name : trans("main.not_found");
            }) */
            ->addColumn('show', 'dashboard.products.buttons.show')
            ->addColumn('edit', 'dashboard.products.buttons.edit')
            ->addColumn('delete', 'dashboard.products.buttons.delete')
            ->rawColumns(['checkbox', 'status', 'show', 'edit', 'delete']);
    }




    public function query(Product $model)
    {
 
        if (auth()->user()->isAdmin()) {

            return $model->newQuery()
                ->when(count($this->categorizations) > 0, function ($q) {
                    $q->whereIn('categorization_id', $this->categorizations);
                })
                ->when($this->company, function ($q) {
                    $q->where('creator_id', $this->company);
                })
                ->byCountry()
                ->with('categorization')
                ->select('products.*');
        } else {
            return $model->newQuery()
                ->when(count($this->categorizations) > 0, function ($q) {
                    $q->whereIn('categorization_id', $this->categorizations);
                })
                ->byCountry()  
                ->with('categorization') 
                ->where('creator_id', auth()->id())
                ->select('products.*');
        }
    }

    public function html()
    {

        $selectFields = [
            [
                'index_num' => 5,
                'selectValues' => [
                    Product::ACTIVE_STATUS      => trans('main.active'),
                    Product::INACTIVE_STATUS    => trans('main.pending'),
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
                'name' => "ar_name",
                'data'    => 'ar_name',
                'title'   => trans('main.name'),
                'searchable' => true,
                'orderable'  => true,
                'width'          => '200px',
            ],
            [
                'name'       => "price",
                'data'       => 'price',
                'title'      => trans('main.price'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '100px',
            ],
            [
                'name'       => "discount_percentage",
                'data'       => 'discount_percentage',
                'title'      => trans('main.discount_percentage'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '100px',
            ],
            [
                'name'       => "price_after_discount",
                'data'       => 'price_after_discount',
                'title'      => trans('main.price_after_discount'),
                'searchable' => true,
                'orderable'  => true,
                'width'      => '100px',
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
                'name' => "categorization.ar_name",
                'data'    => 'categorization.ar_name',
                'title'   => trans('main.categorization'),
                'searchable' => true,
                'orderable'  => true,
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
        return 'Product_' . date('YmdHis');
    }
}
