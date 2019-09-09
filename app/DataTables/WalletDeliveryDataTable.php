<?php

namespace App\DataTables;

use App\Admin;
use App\User;
use Yajra\DataTables\Services\DataTable;

class WalletDeliveryDataTable extends DataTable
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
            ->addColumn('edit', 'backend.users.btn.edit')
            ->addColumn('status', 'backend.users.btn.status')
            ->addColumn('name', 'backend.users.btn.name')
            ->addColumn('date', 'backend.users.btn.date')
            ->addColumn('delete', 'backend.users.btn.delete')
            ->rawColumns([
                'edit',
                'status',
                'name',
                'date',
                'delete',
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
            return Admin::join('wallet_deliveries','wallet_deliveries.delivery_id','=','admins.id')
                ->where('admins.userType','delivery')
                ->select('admins.*','wallet_deliveries.account')->get();
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
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasRole('super_admin') == false ?'btn btn-default disabled':'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
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
        return [

            [
                'name'=>'id',
                'data'=>'id',
                'title'=>trans('admin.ID')
            ],
            [
                'name'=>'account',
                'data'=>'account',
                'title'=>trans('admin.Wallet')
            ],
            [
                'name'=>'name',
                'data'=>'name',
                'title'=>trans('admin.Full name')
            ],

            [
                'name'=>'email',
                'data'=>'email',
                'title'=>trans('admin.E-mail')
            ],
            [
                'name'=>'address',
                'data'=>'address',
                'title'=>trans('admin.Address')
            ],
            [
                'name'=>'phone',
                'data'=>'phone',
                'title'=>trans('admin.Phone')
            ],
            [
                'name'=>'date',
                'data'=>'date',
                'title'=>trans('admin.Create at')
            ],
            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status')
            ],
            [
                'name'=>'edit',
                'data'=>'edit',
                'title'=>trans('admin.Details'),
                'exportable'=>false,
                'printable'=>false,
                'orderable'=>false,
                'searchable'=>false,
            ],
            [
                'name'=>'delete',
                'data'=>'delete',
                'title'=>trans('admin.Wallet'),
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
        return 'User_' . date('YmdHis');
    }
}
