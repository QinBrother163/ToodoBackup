<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TdoTransLog extends Model
{
//    protected $connection = 'mysql54mg_gd';
    protected $fillable = [
        'name',
        'date',
        'flag',
    ];
}
