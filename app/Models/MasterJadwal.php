<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJadwal extends Model
{
    protected $table = 'tb_master_jadwal';

    protected $fillable = [
        'id_tahun',
        'semester',
        'id_kelas',
        'id_mapel',
        'jml_jam',
        'id_gtk',
        'angkatan',
    ];

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'id_kelas',
            'idx'
        );
    }

    public function mapel()
    {
        return $this->belongsTo(
            Mapel::class,
            'id_mapel',
            'id'
        );
    }

    public function guru()
    {
        return $this->belongsTo(
            Gtk::class,
            'id_gtk',
            'id'
        );
    }

    public function tahun()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'id_tahun',
            'id'
        );
    }
}