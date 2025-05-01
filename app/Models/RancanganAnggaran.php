<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RancanganAnggaran extends Model
{
    use HasFactory;
    protected $table = 'rancangan_anggaran';
    protected $fillable = ['departemen_id', 'periode_id', 'jumlah_anggaran', 'status', 'catatan'];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeAnggaran::class, 'periode_id');
    }
}
