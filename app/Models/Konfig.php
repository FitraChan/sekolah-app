<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konfig extends Model
{
    protected $table = 'tb_konfig';

    protected $fillable = [
        'id_tahun',
        'id_gelombang',
        'smt',
        'id_thn_ppdb'
    ];

    public $timestamps = false;
}