<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    protected $table = 'log';
    protected $fillable =[
        'idlog',
        'ip',
        'entrada',
        'salida',
        'tabla',
        'proceso',
        'usuario'
    ];
}
