<?php

namespace App\DataTables;

use App\StatementTransaction;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
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
            ->addColumn('checkbox', 'backend.transaction.btn.checkbox')
            ->addColumn('from_id', 'backend.transaction.btn.from_id')
            ->addColumn('to_id', 'backend.transaction.btn.to_id')
            ->addColumn('from_user_type', 'backend.transaction.btn.from_user_type')
            ->addColumn('to_user_type', 'backend.transaction.btn.to_user_type')
            ->addColumn('status', 'backend.transaction.btn.status')
            ->addColumn('order_id', 'backend.transaction.btn.order_id')
            ->addColumn('due_date', 'backend.transaction.btn.due_date')
            ->addColumn('payment_method', 'backend.transaction.btn.payment_method')
            ->rawColumns([
                'checkbox',
                'from_id',
                'to_id',
                'to_user_type',
                'from_user_type',
                'status',
                'order_id',
                'due_date',
                'payment_method'
            ]);
    }
//OrganizationAccountingTable.php
    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return StatementTransaction::query()
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
                'name'=>'order_id',
                'data'=>'order_id',
                'title'=>trans('admin.Order ID')
            ],
            [
                'name'=>'from_id',
                'data'=>'from_id',
                'title'=>trans('admin.From use name')
            ],
            [
                'name'=>'from_user_type',
                'data'=>'from_user_type',
                'title'=>trans('admin.From User Type'),

            ],
            [
                'name'=>'to_id',
                'data'=>'to_id',
                'title'=>trans('admin.To user name')
            ],
            [
                'name'=>'to_user_type',
                'data'=>'to_user_type',
                'title'=>trans('admin.To User Type'),

            ],
            [
                'name'=>'payment_method',
                'data'=>'payment_method',
                'title'=>trans('admin.Payment Method'),

            ],
            [
                'name'=>'amount',
                'data'=>'amount',
                'title'=>trans('admin.Amount'),

            ],
            [
                'name'=>'due_date',
                'data'=>'due_date',
                'title'=>trans('admin.Due Date'),

            ],
            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status'),

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
        return 'Transaction_' . date('YmdHis');
    }
}
