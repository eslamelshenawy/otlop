<?php

namespace App\DataTables;

use App\Admin;
use App\City;
use App\CityTranslation;
use App\Restaurant;
use App\RestaurantTranslation;
use App\TypeTranslation;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class RestaurantDataTable extends DataTable
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
            ->addColumn('checkbox', 'backend.restaurants.btn.checkbox')
            ->addColumn('features', 'backend.restaurants.btn.features')
            ->addColumn('edit', 'backend.restaurants.btn.edit')
            ->addColumn('status', 'backend.restaurants.btn.status')
            ->addColumn('delete', 'backend.restaurants.btn.delete')
            ->rawColumns([
                'features',
                'edit',
                'status',
                'delete',
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

        return RestaurantTranslation::query()
            ->join('restaurants','restaurant_translations.restaurant_id','=','restaurants.id')
            ->join('city_translations','city_translations.city_id','=','restaurants.city_id')
            ->join('type_translations','type_translations.type_id','=','restaurants.type_id')
            ->join('admins','admins.id','=','restaurants.admin_id')
            ->where('restaurant_translations.locale',\App::getLocale())
            ->where('city_translations.locale',\App::getLocale())
            ->where('type_translations.locale',\App::getLocale())
            ->select('restaurants.id as id',
                'restaurant_translations.name as name',
                \DB::raw("CONCAT(admins.firstName,' ',admins.lastName) as admin"),
                'restaurants.status as status',
                'city_translations.name as city',
                'restaurants.features_type as features',
                'type_translations.name as type','restaurants.status as status');

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
                        'text'=>'<i class="fa fa-plus-circle"></i> '.trans('admin.Create Restaurant'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('create_restaurants') == false ? 'btn btn-info disabled' : 'btn btn-info'  ,
                        "action"=>"function(){
                                window.location.href = '".\URL::current()."/create';
                                }"
                    ],
                    ['extend' =>'print','className'=>'btn btn-primary','text'=>'<i class="fa fa-print"></i> '.trans('admin.Print')],
                    ['extend' =>'csv','className'=>'btn btn-info','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export CSV')],
                    ['extend' =>'excel','className'=>'btn btn-success','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export Excel')],
                    [
                        'extend' =>'reload',
                        'className'=>\Auth::guard('admin')->user()->hasPermission('read_restaurants') == false ? 'btn btn-default disabled':'btn btn-default'
                        ,'text'=>'<i class="fa fa-refresh"></i>'],
                    [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('delete_restaurants') == false ? 'btn btn-danger delBtn disabled':'btn btn-danger delBtn',
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
                'name'=>'features',
                'data'=>'features',
                'title'=>trans('admin.features'),
            ],
            [
                'name'=>'name',
                'data'=>'name',
                'title'=>trans('admin.Restaurant name')
            ],
         
           
            [
                'name'=>'admin',
                'data'=>'admin',
                'title'=>trans('admin.Restaurant owner'),
                'searchable'=>false,
            ],
            [
                'name'=>'type',
                'data'=>'type',
                'title'=>trans('admin.Type restaurant'),
                'searchable'=>false,
            ],
            [
                'name'=>'city',
                'data'=>'city',
                'title'=>trans('admin.City name'),
                'searchable'=>false,
            ],
            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status'),
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
        return 'Restaurant_' . date('YmdHis');
    }
}
