<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UjianPesertaCalon extends Model
{
    protected $table = 'tb_ujian_peserta_calon';

    protected $fillable = [
        'id_ujian',
        'calon_siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'nilai',
        'jumlah_benar',
        'jumlah_salah',
        'tidak_dijawab',
        'status',
        'hasil',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(UjianCalon::class, 'id_ujian');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(UjianJawabanCalon::class, 'peserta_id');
    }

    public function calonSiswa(): BelongsTo
    {
        return $this->belongsTo(
            CalonSiswa::class,
            'calon_siswa_id'
        );
    }
}