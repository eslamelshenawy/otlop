<?php

namespace App\DataTables;

use App\Admin;
use App\City;
use App\CityTranslation;
use App\Privacy;
use App\PrivacyTranslation;
use App\Question;
use App\QuestionTranslation;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class PrivacyDataTable extends DataTable
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
            ->addColumn('checkbox', 'backend.privacy.btn.checkbox')
            ->addColumn('edit', 'backend.privacy.btn.edit')
            ->addColumn('status', 'backend.privacy.btn.status')
            ->addColumn('delete', 'backend.privacy.btn.delete')
            ->rawColumns([
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

        return PrivacyTranslation::query()->join('privacies','privacy_translations.privacy_id','=','privacies.id')
            ->where('privacy_translations.locale',\App::getLocale())
            ->select('privacies.id as id','privacy_translations.title as title','privacies.status as status');

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
            ->parameters([
                'dom'=>'Blfrtip', // excel sheet and export
                'lengthMenu'=>[[10,25,50,100],[10,25,50,trans('admin.All Record')]], // show and search in table
                'buttons'=>[
                    [
                        'text'=>'<i class="fa fa-plus-circle"></i> '.trans('admin.Create privacy'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('create_privacy') == false ? 'btn btn-info disabled' : 'btn btn-info' ,
                        "action"=>"function(){
                                window.location.href = '".\URL::current()."/create';
                                }"
                    ],
                    ['extend' =>'print','className'=>'btn btn-primary','text'=>'<i class="fa fa-print"></i> '.trans('admin.Print')],
                    ['extend' =>'csv','className'=>'btn btn-info','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export CSV')],
                    ['extend' =>'excel','className'=>'btn btn-success','text'=>'<i class="fa fa-file"></i> '.trans('admin.Export Excel')],
                    ['extend' =>'reload','className'=>\Auth::guard('admin')->user()->hasPermission('read_privacy') == false ? 'btn btn-default disabled' :'btn btn-default','text'=>'<i class="fa fa-refresh"></i>'],
                    [
                        'text'=>'<i class="fa fa-trash "></i> '.trans('admin.Delete All'),
                        'className'=>\Auth::guard('admin')->user()->hasPermission('delete_privacy') == false ? 'btn btn-danger disabled delBtn':'btn btn-danger delBtn',
                    ],
                ],
                'language'=>dataTaleLang(),
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
                'name'=>'title',
                'data'=>'title',
                'title'=>trans('admin.Title')
            ],
            [
                'name'=>'status',
                'data'=>'status',
                'title'=>trans('admin.Status')
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
        return 'privacy_' . date('YmdHis');
    }
}
