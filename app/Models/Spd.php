<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spd extends Model
{
    use HasFactory;

    protected $table = 'surat_perjalanan_dinas';
    protected $fillable = [
        'departemen_id',
        'periode_id',
        'user_id',
        'pegawai_id',
        'nomor_spd',
        'nama_pegawai',
        'asal',
        'tujuan',
        'kegiatan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'jenis_transport',
        'nama_transport',
        'status',
        'uraian',
        'tanggal_deklarasi'
    ];
    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
    public function periode()
    {
        return $this->belongsTo(PeriodeAnggaran::class, 'periode_id');
    }
    public function details()
    {
        return $this->hasMany(SpdDetail::class, 'spd_id');
    }
}
