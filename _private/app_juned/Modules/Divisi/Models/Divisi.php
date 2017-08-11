<?php

namespace Modules\Divisi\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mod_divisi';

    protected $fillable = ['nama', 'url', 'status', 'created_by', 'updated_by'];
}
