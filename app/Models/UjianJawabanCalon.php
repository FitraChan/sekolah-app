<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianJawabanCalon extends Model
{
    protected $table = 'tb_ujian_jawaban_calon';

    protected $fillable = [
        'peserta_id',
        'soal_id',
        'jawaban',
        'benar',
        'nilai',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'nilai' => 'decimal:2',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(UjianPesertaCalon::class, 'peserta_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(UjianSoalCalon::class, 'soal_id');
    }
}