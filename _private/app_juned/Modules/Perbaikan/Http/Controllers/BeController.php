<?php
namespace Modules\Perbaikan\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\Perbaikan\Models\Perbaikan,
    Modules\Pengadaan\Models\Penanganan,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    var $type = 'PERBAIKAN';

    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management Perbaikan
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['countAll'] = Perbaikan::where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = Perbaikan::where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;
            
            return view('perbaikan::index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? Perbaikan::where('status', '-1') : Perbaikan::where('status', '<>', '-1');

            return Datatables::of($rows)
            ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->judul, 'default', $isTrash); })
            ->editColumn('status', function ($r) { return $r->status=='1' ? trans('global.active') : trans('global.inactive'); })
            ->editColumn('created_at', function ($r) { return formatDate($r->created_at, 5); })
            ->editColumn('updated_at', function ($r) { return $r->updated_at ? formatDate($r->updated_at, 5) : '-'; })
            ->make(true);
        }
    }

    public function trash()
    {
        return $this->index(true);
    }

    /*
    |--------------------------------------------------------------------------
    | Build Form
    |--------------------------------------------------------------------------
    */
    public function form($id='')
    {
        $data = $id ? Perbaikan::with('relpenanganan')->find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : []; 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('perbaikan::form'.(session::get('ses_is_teknisi')?'_teknisi':''), $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Pengadaan(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Restore
    |--------------------------------------------------------------------------
    */
    function restore($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Pengadaan(), $id, ['status'=>'1']), 
            'message'=> $this->_buildNotification(true)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Save Data | Insert Or Update
    |--------------------------------------------------------------------------
    */
    function save()
    {
        $input  = Input::except('_token');
        
        $input['status'] = val($input, 'status') ? 1 : 0;
        $teknisi = val($input, 'id_teknisi'); unset($input['id_teknisi']);
        
        //FORMAT DATE
        foreach( ['tgl_selesai','tgl_permintaan'] as $t )
        {
            if ( val($input, $t) )
            {
                $input[$t] = $input[$t] && trim($input[$t]) ? date("Y-m-d H:i:s", strtotime($input[$t])) : NULL;
            }
        }

        //jenis_permintaan
        if ( val($input, 'jenis_permintaan') )
        {
            $input['jenis_permintaan'] = implode(',', $input['jenis_permintaan']);
        }


        $status = $this->_saveData( new Perbaikan(), [   
            //VALIDATOR
            "judul" => "required|unique:mod_perbaikan". ($input['id'] ? ",judul,".$input['id'] : '')
        ], $input, 'judul');

        //Teknisi
        if ( $status && $teknisi )
        {
            //delete Old teknisi
            Penanganan::where('id_permintaan', $status)->where('tipe', $this->type)->delete();

            $dataTeknisi = [];

            foreach( explode(',', $teknisi) as $t )
            {
                $dataTeknisi[] = ['id_permintaan'=>$status, 'id_user'=>$t, 'tipe'=>$this->type];
            }
            Penanganan::insert($dataTeknisi);
        }

        return Redirect( BeUrl( config('perbaikan.info.alias') .(!$status ? ($input['id']?'/edit/'.$input['id']:'/add') : '') ) );
    }
}