<?php

namespace App\DataTables;

use App\Admin;
use App\City;
use App\CityTranslation;
use App\RatingRestaurant;
use App\Opinion;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class OpinionDataTable extends DataTable
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
            ->addColumn('checkbox', 'backend.opinion.btn.checkbox')
            ->addColumn('delete', 'backend.opinion.btn.delete')
            ->addColumn('evaluation', 'backend.opinion.btn.evaluation')
            ->addColumn('effort', 'backend.opinion.btn.effort')
            ->addColumn('eye', 'backend.opinion.btn.eye')
            ->addColumn('restaurant_id', 'backend.opinion.btn.restaurant_name')
            ->rawColumns([
                'delete',
                'checkbox',
                'evaluation',
                'effort',
                'eye',
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

        return RatingRestaurant::query();
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
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasRole('super_admin') == false ? 'btn btn-default disabled' :'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
                    [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasRole('super_admin') == false ? 'btn btn-danger disabled delBtn':'btn btn-danger delBtn',
                    ],
                ],
                'language'=>dataTaleLang()
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
                'name'=>'email',
                'data'=>'email',
                'title'=>trans('admin.effort')
            ],
            [
                'name'=>'rating',
                'data'=>'rating',
                'title'=>trans('admin.evaluation')
            ],
            [
                'name'=>'comment',
                'data'=>'comment',
                'title'=>trans('admin.comments')
            ],
            [
                'name'=>'restaurant_id',
                'data'=>'restaurant_id',
                'title'=>trans('admin.restaurant')
            ],
            [
                'name'=>'eye',
                'data'=>'eye',
                'title'=>trans('admin.Message'),
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
        return 'Opinion_' . date('YmdHis');
    }
}
