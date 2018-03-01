<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TdoTrans104Table extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'field',
        'primary',
        'verify',
    ];
}
