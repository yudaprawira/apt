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

    protected $fillable = ['judul', 'url', 'status', 'created_by', 'updated_by'];
}
