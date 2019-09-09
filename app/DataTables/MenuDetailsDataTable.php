<?php

namespace App\DataTables;

use App\Admin;
use App\City;
use App\CityTranslation;
use App\Menu;
use App\MenuDetailsTranslation;
use App\MenuTranslation;
use App\TypeTranslation;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class MenuDetailsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('checkbox', 'backend.vendor.menu-details.btn.checkbox')
            ->addColumn('edit', 'backend.vendor.menu-details.btn.edit')
            ->addColumn('status', 'backend.vendor.menu-details.btn.status')
            ->addColumn('image', 'backend.vendor.menu-details.btn.image')
            ->addColumn('delete', 'backend.vendor.menu-details.btn.delete')
            ->addColumn('eye', 'backend.vendor.menu-details.btn.eye')
            ->rawColumns([
                'edit',
                'status',
                'image',
                'delete',
                'eye',
                'checkbox'
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {

        return MenuDetailsTranslation::query()->join('menu_details','menu_details_translations.menu_details_id','=','menu_details.id')
            ->where('menu_details_translations.locale','=',\App::getLocale())
            ->where('menu_details.restaurant_id','=',getRestaurant(\Auth::guard('admin')->user()->id)->id)
            ->select('menu_details.id as id','menu_details_translations.name as name','menu_details.status as status','menu_details.price as price'
            ,'menu_details.image as image');

    }

    public static function lang()
    {
        $langJson = dataTaleLang();
        return $langJson;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->addAction(['width' => '80px'])
            /*->parameters($this->getBuilderParameters())*/
            ->parameters([
                'dom'=>'Blfrtip', // excel sheet and export
                'lengthMenu'=>[[10,25,50,100],[10,25,50,trans('admin.All Record')]], // show and search in table
                'buttons'=>[
                    [
                        'text'=>'<i class="fa fa-plus-circle"></i> '.trans('admin.Create Meal'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('create_menu') == false ? 'btn btn-info disabled' : 'btn btn-info' ,
                        "action"=>"function(){
                                window.location.href = '".\URL::current()."/create';
                                }"
                    ],
                    ['extend' =>'print','className'=>'btn btn-primary','text'=>'<i class="fa fa-print"></i> '.trans('admin.Print')],
                    ['extend' =>'csv','className'=>'btn btn-info','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export CSV')],
                    ['extend' =>'excel','className'=>'btn btn-success','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export Excel')],
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasPermission('read_menu') == false ? 'btn btn-default disabled':'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
                    [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('delete_menu') == false ? 'btn btn-danger disabled delBtn':'btn btn-danger delBtn',
                    ],
                ],
                /*'initComplete'=>"function () {
                                this.api().columns([]).every(function () {
                                    var column = this;
                                    var input = document.createElement(\"input\");
                                    $(input).appendTo($(column.footer()).empty())
                                    .on('keyup', function () {
                                        column.search($(this).val(), false, false, true).draw();
                                    });
                                });
                            }",*/
                'language'=>dataTaleLang()//self::lang(),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [

            [
                'name'=>'checkbox',
                'data'=>'checkbox',
                'title'=>'<input type="checkbox" class="check_all" onclick="check_all()">',
                'exportable'=>false,
                'printable'=>false,
                'orderable'=>false,
                'searchable'=>false,
            ],
            [
                'name'=>'id',
                'data'=>'id',
                'title'=>trans('admin.ID')
            ],
            [
                'name'=>'image',
                'data'=>'image',
                'title'=>trans('admin.IMG')
            ],
            [
                'name'=>'name',
                'data'=>'name',
                'title'=>trans('admin.Menu name')
            ],
            [
                'name'=>'price',
                'data'=>'price',
                'title'=>trans('admin.Price')
            ],
            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status')
            ],
            [
                'name'=>'eye',
                'data'=>'eye',
                'title'=>trans('admin.View'),
                'exportable'=>false,
                'printable'=>false,
                'orderable'=>false,
                'searchable'=>false,
            ],
            [
                'name'=>'edit',
                'data'=>'edit',
                'title'=>trans('admin.Edit'),
                'exportable'=>false,
                'printable'=>false,
                'orderable'=>false,
                'searchable'=>false,
            ],
            [
                'name'=>'delete',
                'data'=>'delete',
                'title'=>trans('admin.Delete'),
                'exportable'=>false,
                'printable'=>false,
                'orderable'=>false,
                'searchable'=>false,
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
        return 'MenuDetails_' . date('YmdHis');
    }
}
