<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['username', 'email', 'status', 'level_id', 'divisi_id', 'password', 'created_by', 'updated_by'];

    function rellevel()
    {
        return $this->hasOne('App\Models\System\Level', 'id', 'level_id');
    }   

    function reldivisi()
    {
        return $this->hasOne('Modules\Divisi\Models\Divisi', 'id', 'divisi_id');
    }   
}
