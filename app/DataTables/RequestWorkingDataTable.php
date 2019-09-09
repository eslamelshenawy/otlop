<?php

namespace App\DataTables;

use App\Admin;
use App\Blog;
use App\City;
use App\CityTranslation;
use App\RequestWorking;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class RequestWorkingDataTable extends DataTable
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
            ->addColumn('checkbox', 'backend.request.btn.checkbox')
            ->addColumn('edit', 'backend.request.btn.edit')
            ->addColumn('status', 'backend.request.btn.status')
            ->addColumn('city', 'backend.request.btn.city')
            ->addColumn('state', 'backend.request.btn.state')
            ->addColumn('type', 'backend.request.btn.type')
            ->addColumn('delete', 'backend.request.btn.delete')
            ->rawColumns([
                'edit',
                'status',
                'city',
                'type',
                'state',
                'delete',
                'checkbox',
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
        return RequestWorking::query()
            ->orderBy('created_at','DESC')->get();
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
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasPermission('read_request') == false ? 'btn btn-default disabled' :'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
                    [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('delete_request') == false ? 'btn btn-danger disabled delBtn':'btn btn-danger delBtn',
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
                'name'=>'name',
                'data'=>'name',
                'title'=>trans('admin.Full name')
            ],
            [
                'name'=>'type',
                'data'=>'type',
                'title'=>trans('admin.Type user')
            ],
            [
                'name'=>'city',
                'data'=>'city',
                'title'=>trans('admin.City name')
            ],
            [
                'name'=>'state',
                'data'=>'state',
                'title'=>trans('admin.State name')
            ],
            [
                'name'=>'address',
                'data'=>'address',
                'title'=>trans('admin.Address')
            ],

            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status'),

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
        return 'RequestWorking_' . date('YmdHis');
    }
}
