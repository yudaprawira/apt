<?php

namespace App\Http\Controllers\BE;

use session,
    Illuminate\Http\Request,
    App\Models\System\Log,
    App\Models\System\User,
    App\Http\Requests,
    Yajra\Datatables\Datatables;    

class DashboardController extends BaseController
{
    function __construct() {
        parent::__construct();        
    }
    
    function homepage()
    {
        $totalPending = \Modules\Pengadaan\Models\Pengadaan::where('status', '1')->where('status_kerja', 'PENDING')->count();
        $totalPending+= \Modules\Perbaikan\Models\Perbaikan::where('status', '1')->where('status_kerja', 'PENDING')->count();

        $totalProgress = \Modules\Pengadaan\Models\Pengadaan::where('status', '1')->where('status_kerja', 'PROGRESS')->count();
        $totalProgress+= \Modules\Perbaikan\Models\Perbaikan::where('status', '1')->where('status_kerja', 'PROGRESS')->count();
        
        $totalSelesai = \Modules\Pengadaan\Models\Pengadaan::where('status', '1')->where('status_kerja', 'SELESAI')->count();
        $totalSelesai+= \Modules\Perbaikan\Models\Perbaikan::where('status', '1')->where('status_kerja', 'SELESAI')->count();
        
        $this->dataView['totalPengadaan'] = formatNumber(\Modules\Pengadaan\Models\Pengadaan::where('status', '1')->count());
        $this->dataView['totalPerbaikan'] = formatNumber(\Modules\Perbaikan\Models\Perbaikan::where('status', '1')->count());
        $this->dataView['totalProgress'] = formatNumber($totalProgress);
        $this->dataView['totalSelesai'] = formatNumber($totalSelesai);
        
        return view($this->tmpl . 'dashboard.default', $this->dataView);
        
    }

    function switchProfile($id)
    {
        if ( val(session::get('ses_switch_to'), $id) )
        {
            session::put('ses_switch_active', $id);
        }

        return Redirect(val($_SERVER, 'HTTP_REFERER', BeUrl()));
    }
    
    public function systemLog()
    {
        if ( empty($_POST) )
        {
            return view($this->tmpl . 'system.log', $this->dataView);    
        }
        else
        {
            $user = getRowArray(User::get(), 'id', 'username');
            return Datatables::of(Log::query()->orderBy('created_at', 'DESC'))
                                ->addColumn('created_by', function ($r) use ($user) { return $user[$r->created_by]; })
                                ->make(true);
        }
    }
}
