<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReimburseRule extends Model
{
    protected $fillable = ['pegawai_level_id', 'jenis_biaya', 'maksimal_biaya', 'keterangan'];

    public function pegawaiLevel()
    {
        return $this->belongsTo(PegawaiLevel::class);
    }
}
