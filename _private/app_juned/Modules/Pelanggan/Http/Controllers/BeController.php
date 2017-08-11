<?php
namespace Modules\Pelanggan\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\Pelanggan\Models\Pelanggan,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management Pelanggan
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            $this->dataView['countAll'] = Pelanggan::where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = Pelanggan::where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;

            return view('pelanggan::index', $this->dataView);
        }
        else
        {
            $rows = $isTrash ? Pelanggan::where('status', '-1') : Pelanggan::where('status', '<>', '-1');

            return Datatables::of($rows)
            ->addColumn('action', function ($r) use ($isTrash) { return $this->_buildAction($r->id, $r->nama, 'default', $isTrash); })
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
        $data = $id ? Pelanggan::find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : []; 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('pelanggan::form', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        return Response()->json([ 
            'status' => $this->_deleteData(new Pelanggan(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
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
            'status' => $this->_deleteData(new Pelanggan(), $id, ['status'=>'1']), 
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
        
        $input['url'] = str_slug(trim($input['nama']));
        $input['status'] = val($input, 'status') ? 1 : 0;

        $image = isset($input['image']) ? $input['image'] : null; unset($input['image']);
        
        if ( $image && is_string($image) )
        {
            $input['image'] = $image; $image = null;
        }
        
        $status = $this->_saveData( new Pelanggan(), [   
            //VALIDATOR
            "nama" => "required"
        ], $input, 'nama');

        if ( $status && $image )
        {
            $image = $this->_uploadImage($image, 'pelanggan', ['600x800', '200x300'], $input['url']);
            
            if ( isset($image['600x800']) )
            {
                Pelanggan::where('id', $status)->update(['image'=>$image['600x800']]);
            }
        }

        $this->clearCache( config('pelanggan.info.alias').'/'.$input['url'].'.html' );

        return Redirect( BeUrl( config('pelanggan.info.alias') .(!$status ? ($input['id']?'/edit/'.$input['id']:'/add') : '') ) );
    }
}