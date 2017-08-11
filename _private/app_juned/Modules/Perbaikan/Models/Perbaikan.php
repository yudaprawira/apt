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

    protected $fillable = ['judul', 'url', 'status', 'created_by', 'updated_by'];
}
