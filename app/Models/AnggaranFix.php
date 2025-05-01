<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggaranFix extends Model
{
    use HasFactory;
    protected $table ="anggaran_fix";

    protected $fillable = [
        'departemen_id',
        'periode_id',
        'jumlah_anggaran',
    ];
}
