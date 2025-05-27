<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nopekerja',
        'nama_pegawai',
        'email'
    ];

    public function spd()
    {
        return $this->hasMany(Spd::class);
    }
    public function pegawai_level()
    {
        return $this->belongsTo(PegawaiLevel::class, 'pegawai_level_id');
    }
}
