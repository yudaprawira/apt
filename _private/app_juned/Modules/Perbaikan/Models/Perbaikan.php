<?php

namespace Modules\Perbaikan\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_perbaikan';

    protected $fillable = ['judul', 'tgl_permintaan', 'id_pelanggan', 'jenis_permintaan', 'permintaan', 'status_kerja', 'tgl_selesai', 'id_user', 'keterangan', 'status', 'created_by', 'updated_by'];

    function relpenanganan()
    {
        return $this->hasMany('\Modules\Pengadaan\Models\Penanganan', 'id_permintaan', 'id')
                    ->join('user', 'user.id', '=', 'penanganan.id_user')
                    ->where('tipe', 'PERBAIKAN');
    }
}
