<?php

namespace App\DataTables;

use App\Admin;
use App\City;
use App\CityTranslation;
use App\Menu;
use App\MenuTranslation;
use App\Order;
use App\TypeTranslation;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        if (\Auth::guard('admin')->user()->userType == 'super_admin' || \Auth::guard('admin')->user()->userType == 'admin')
        {
            return datatables($query)
                ->addColumn('checkbox', 'backend.vendor.order.btn.checkbox')
                ->addColumn('edit', 'backend.vendor.order.btn.edit')
                ->addColumn('status', 'backend.vendor.order.btn.status')
                ->addColumn('user', 'backend.vendor.order.btn.user')
                ->addColumn('amount', 'backend.vendor.order.btn.amount')
                ->addColumn('date', 'backend.vendor.order.btn.date')
                ->addColumn('time', 'backend.vendor.order.btn.time')
                ->addColumn('total', 'backend.vendor.order.btn.total')
                ->addColumn('delivery', 'backend.vendor.order.btn.delivery')
                ->addColumn('delete', 'backend.vendor.order.btn.delete')
                ->addColumn('restaurant', 'backend.vendor.order.btn.restaurant')
                ->rawColumns([
                    'edit',
                    'status',
                    'delete',
                    'checkbox',
                    'user',
                    'amount',
                    'date',
                    'time',
                    'total',
                    'delivery',
                    'restaurant'
                ]);
        }
        else
        {
            return datatables($query)
                ->addColumn('checkbox', 'backend.vendor.order.btn.checkbox')
                ->addColumn('edit', 'backend.vendor.order.btn.edit')
                ->addColumn('status', 'backend.vendor.order.btn.status')
                ->addColumn('user', 'backend.vendor.order.btn.user')
                ->addColumn('amount', 'backend.vendor.order.btn.amount')
                ->addColumn('date', 'backend.vendor.order.btn.date')
                ->addColumn('time', 'backend.vendor.order.btn.time')
                ->addColumn('total', 'backend.vendor.order.btn.total')
                ->addColumn('delivery', 'backend.vendor.order.btn.delivery')
                ->addColumn('delete', 'backend.vendor.order.btn.delete')
                ->rawColumns([
                    'edit',
                    'status',
                    'delete',
                    'checkbox',
                    'user',
                    'amount',
                    'date',
                    'time',
                    'total',
                    'delivery'
                ]);
        }

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if (\Auth::guard('admin')->user()->userType == 'super_admin' || \Auth::guard('admin')->user()->userType == 'admin')
        {
            return Order::query();
        }
        else
        {
            return Order::query()
                ->where('restaurant_id',getDataRestaurant(\Auth::guard('admin')->user()->id)->id)
                ->get();
        }

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
                    ['extend' =>'print','className'=>'btn btn-primary','text'=>'<i class="fa fa-print"></i> '.trans('admin.Print')],
                    ['extend' =>'csv','className'=>'btn btn-info','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export CSV')],
                    ['extend' =>'excel','className'=>'btn btn-success','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export Excel')],
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasPermission('read_menu') == false ? 'btn btn-default disabled':'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
                   /* [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('delete_menu') == false ? 'btn btn-danger disabled delBtn':'btn btn-danger delBtn',
                    ],*/
                ],
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
        if (\Auth::guard('admin')->user()->userType == 'super_admin' || \Auth::guard('admin')->user()->userType == 'admin')
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
                    'title'=>trans('admin.OrderID#')
                ],
                [
                    'name'=>'user',
                    'data'=>'user',
                    'title'=>trans('admin.Customer Name')
                ],
                [
                    'name'=>'time',
                    'data'=>'time',
                    'title'=>trans('admin.Time')
                ],
                [
                    'name'=>'date',
                    'data'=>'date',
                    'title'=>trans('admin.Date')
                ],
                [
                    'name'=>'amount',
                    'data'=>'amount',
                    'title'=>trans('admin.Amount')
                ],
                [
                    'name'=>'delivery',
                    'data'=>'delivery',
                    'title'=>trans('admin.Delivery Vai')
                ],
                [
                    'name'=>'total',
                    'data'=>'total',
                    'title'=>trans('admin.Total Order')
                ],
                [
                    'name'=>'status',
                    'data'=>'status',
                    'title'=>trans('admin.Status')
                ],
                [
                    'name'=>'restaurant',
                    'data'=>'restaurant',
                    'title'=>trans('admin.Restaurant name')
                ],
                [
                    'name'=>'edit',
                    'data'=>'edit',
                    'title'=>trans('admin.View'),
                    'exportable'=>false,
                    'printable'=>false,
                    'orderable'=>false,
                    'searchable'=>false,
                ],
                /*[
                    'name'=>'delete',
                    'data'=>'delete',
                    'title'=>trans('admin.Delete'),
                    'exportable'=>false,
                    'printable'=>false,
                    'orderable'=>false,
                    'searchable'=>false,
                ],*/

            ];
        }
        else
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
                    'title'=>trans('admin.OrderID#')
                ],
                [
                    'name'=>'user',
                    'data'=>'user',
                    'title'=>trans('admin.Customer Name')
                ],
                [
                    'name'=>'time',
                    'data'=>'time',
                    'title'=>trans('admin.Time')
                ],
                [
                    'name'=>'date',
                    'data'=>'date',
                    'title'=>trans('admin.Date')
                ],
                [
                    'name'=>'amount',
                    'data'=>'amount',
                    'title'=>trans('admin.Amount')
                ],
                [
                    'name'=>'delivery',
                    'data'=>'delivery',
                    'title'=>trans('admin.Delivery Vai')
                ],
                [
                    'name'=>'total',
                    'data'=>'total',
                    'title'=>trans('admin.Total Order')
                ],
                [
                    'name'=>'status',
                    'data'=>'status',
                    'title'=>trans('admin.Status')
                ],
                [
                    'name'=>'edit',
                    'data'=>'edit',
                    'title'=>trans('admin.View'),
                    'exportable'=>false,
                    'printable'=>false,
                    'orderable'=>false,
                    'searchable'=>false,
                ],
                /*[
                    'name'=>'delete',
                    'data'=>'delete',
                    'title'=>trans('admin.Delete'),
                    'exportable'=>false,
                    'printable'=>false,
                    'orderable'=>false,
                    'searchable'=>false,
                ],*/

            ];
        }

    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Orders_' . date('YmdHis');
    }
}
