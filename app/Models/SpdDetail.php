<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpdDetail extends Model
{
    use HasFactory;

    protected $table = 'spd_detail';

    protected $fillable = [
        'spd_id',
        'jenis_biaya',
        'nominal',
        'keterangan',
    ];

    public function spd()
    {
        return $this->belongsTo(Spd::class, 'spd_id');
    }
}
