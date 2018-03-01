<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TdoTransTable extends Model
{
//    protected $connection = 'mysql54mg_gd';
//    protected $table = 'tdo_trans_tables';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'field',
        'primary',
        'verify',
    ];
}
