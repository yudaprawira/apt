<?php
namespace Modules\PustakaGambar\Http\Controllers;

use Illuminate\Routing\Controller,
    App\Http\Controllers\BE\BaseController,
    Modules\PustakaGambar\Models\PustakaGambar,
    Yajra\Datatables\Datatables;

use Input, Session, Request, Redirect;

class BeController extends BaseController
{
    function __construct() {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | Management PustakaGambar
    |--------------------------------------------------------------------------
    */
    public function index($isTrash=false)
    {
        if ( Request::isMethod('get') )
        {
            
            $rows = PustakaGambar::query();

            $rows_ = clone $rows;

            $this->dataView['countAll'] = $rows->where('status', '<>', '-1')->count();
            $this->dataView['countTrash'] = $rows_->where('status', '-1')->count();

            $this->dataView['isTrash'] = $isTrash;

            $rowCategory = PustakaGambar::select('kategori')->where('kategori', '<>', '');

            if ( $isTrash )
            {
                $rowCategory->where('status', '-1');
            }
            else
            {
                $rowCategory->where('status', '<>', '-1');
            }

            $this->dataView['form'] = $this->form();
            $this->dataView['rowImages'] = $this->_getRowImage(12, $isTrash);
            $this->dataView['kategori'] = getRowArray($rowCategory->orderBy('kategori', 'ASC')->distinct()->get(),'kategori','kategori');
            
            return view('pustakagambar::index', $this->dataView);
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
        $data = $id ? PustakaGambar::find($id) : null;
        
        $this->dataView['dataForm'] = $data ? $data->toArray() : []; 
        
        $this->dataView['dataForm']['form_title'] = $data ? trans('global.form_edit') : trans('global.form_add');

        return view('pustakagambar::form', $this->dataView);
    }

    /*
    |--------------------------------------------------------------------------
    | Build Lookup Image Selector
    |--------------------------------------------------------------------------
    */
    public function lookup()
    {
        $row = [
            'images' => $this->_getRowImage(8),
            'kategori' => getRowArray(PustakaGambar::select('kategori')->where('status', '<>', '-1')->where('kategori', '<>', '')->orderBy('kategori', 'ASC')->distinct()->get(),'kategori','kategori')
        ];

        return view('pustakagambar::lookup', $row);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */
    function delete($id)
    {
        if ( (val($_GET, 'permanent')=='1' ) )
        {
            //delete file
            $path = PustakaGambar::where('id', $id)->first();

            if ( $path && file_exists(public_path('media/'.$path->image)) && is_file(public_path('media/'.$path->image)))
            {
                unlink(public_path('media/'.$path->image));
            }
        }
        return Response()->json([ 
            'status' => $this->_deleteData(new PustakaGambar(), $id, (val($_GET, 'permanent')=='1' ? null : ['status'=>'-1'])), 
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
            'status' => $this->_deleteData(new PustakaGambar(), $id, ['status'=>'1']), 
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
        $image  = isset($input['file']) && $input['file']!='undefined' ? $input['file'] : null;unset($input['file']);
        
        if ( val($input, '_post') )
        {
            parse_str($input['_post'], $params);
            $input  = $params;
        }

        unset($input['_image']);
        unset($input['_token']);
        
        if ( !$image && !isset($input['id']) && !$input['id'])
        {
            $this->setNotif(trans('pustakagambar::global.fail_image'), 'danger');
            
            return Response()->json([ 
                'status' => false, 
                'message'=> $this->_buildNotification(true),
                'form'   => null
            ]);
        }
        
        $input['url'] = str_slug(trim($input['keterangan']));
        $input['status'] = val($input, 'status') ? 1 : 0;

        $status = $this->_saveData( new PustakaGambar(), [   
            //VALIDATOR
            "keterangan" => "required",
        ], $input, 'keterangan');

        if ( $image )
        {
            $image = $this->_uploadImage($image, 'pustakagambar', ['664xauto', '200xauto'], $input['url']);
            
            if ( isset($image['664xauto']) )
            {
                PustakaGambar::where('id', $status)->update(['image'=>$image['664xauto'].'?'.time()]);
            }
        }
                
        return Response()->json([ 
            'status' => $status, 
            'result' => [
                'url'=> val($image, '664xauto') ? imgUrl($image['664xauto']) : null,
                'path'=> val($image, '664xauto') ? $image['664xauto'] : null,
                'copyright'=> val($input, 'copyright'),
                'description'=> val($input, 'keterangan'),
            ],
            'message'=> $this->_buildNotification(true),
            'form'   => $status ? base64_encode($this->form()) : null
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GET ROW IMAGE to build list image
    |--------------------------------------------------------------------------
    */
    private function _getRowImage($perItem=12, $isTrash=false)
    {
        $rows = PustakaGambar::orderBy('id', 'DESC');

        if ( val($_GET, 'filter-keterangan') && val($_GET, 'filter-keterangan')!='' )
        {
            $rows->where(function($q){
                foreach( explode(' ', val($_GET, 'filter-keterangan')) as $k )
                {
                    $q->orWhere('keterangan', 'LIKE', '%'.$k.'%');
                }
                return $q;
            });
        }
        
        if ( val($_GET, 'filter-kategori') && val($_GET, 'filter-kategori')!='all' )
        {
            $rows->where('kategori', val($_GET, 'filter-kategori'));
        }

        if ( $isTrash )
        {
            $rows->where('status', '-1');
        }
        else
        {
            $rows->where('status', '<>', '-1');
        }

        return $rows->paginate($perItem);
    }
}