<?php

namespace Modules\Pengadaan\Models;

use Illuminate\Database\Eloquent\Model;

class Pengadaan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_pengadaan';

    protected $fillable = ['judul', 'tgl_permintaan', 'id_pelanggan', 'permintaan', 'status_kerja', 'tgl_selesai', 'id_user', 'keterangan', 'jenis_permintaan', 'status', 'created_by', 'updated_by'];

    function relpenanganan()
    {
        return $this->hasMany('\Modules\Pengadaan\Models\Penanganan', 'id_permintaan', 'id')
                    ->join('user', 'user.id', '=', 'penanganan.id_user')
                    ->where('tipe', 'PENGADAAN');
    }
}
