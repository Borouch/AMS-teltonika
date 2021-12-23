<?php

namespace App\Models;


use Spatie\Permission\Models\Role as Model;
class Role extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
