<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjadwalanHari extends Model
{
    protected $table = 'tb_penjadwalan_hari';

    protected $primaryKey = 'id';

   // public $timestamps = false;

    protected $fillable = [
        'idpenjadwalan',
        'id_hari',
        'id_jam',
    ];

    public function jadwal()
    {
        return $this->belongsTo(
            MasterJadwal::class,
            'idpenjadwalan',
            'id'
        );
    }

   

    public function jam()
    {
        return $this->belongsTo(
            JamPelajaran::class,
            'id_jam',
            'id'
        );
    }
}