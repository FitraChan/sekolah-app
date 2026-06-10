<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bayar extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tb_bayar';

    protected $primaryKey = 'id';

    //public $timestamps = false;

    protected $fillable = [
        'id_tahun',
        'id_bulan',
        'no_kwitansi',
        'tgl_bayar',
        'jam_bayar',
        'id_kasir',
        'approved_by',
        'id_siswa',
        'id_tmp_siswa',
        'keterangan',
        'tot_bayar',
        'bukti_bayar',
        'tot_kwajiban',
        'payment_id',
        'via',
        'sts_trans',
        'id_tmp_bayar',
    ];

    protected $casts = [
        'tgl_bayar' => 'datetime',
        'jam_bayar' => 'datetime:H:i:s',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'id_siswa',
            'nipd'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'id_tahun',
            'id'
        );
    }

    public function kasir()
    {
        return $this->belongsTo(
            User::class,
            'id_kasir',
            'id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by',
            'id'
        );
    }

    public function detail()
    {
        return $this->hasMany(
            DetBayar::class,
            'id_bayar',
            'id'
        );
    }
}