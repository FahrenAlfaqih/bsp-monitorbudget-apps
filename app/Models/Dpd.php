<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dpd extends Model
{
    use HasFactory;

    protected $table = 'deklarasi_perjalanan_dinas';
    protected $fillable = [
        'spd_id',
        'user_id',
        'total_biaya',
        'pr', 
        'po',
        'ses',
        'tanggal_deklarasi',
        'uraian',
    ];

    public function spd()
    {
        return $this->belongsTo(Spd::class, 'spd_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function setTotalBiayaAttribute($value)
    {
        $this->attributes['total_biaya'] = number_format($value, 2, '.', '');
    }
    public function getTotalBiayaAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }
}
