<?php

namespace Modules\Pelanggan\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_pelanggan';

    protected $fillable = ['nama', 'image', 'nama', 'alamat', 'kota', 'telepon', 'email', 'url', 'status', 'created_by', 'updated_by'];
}
