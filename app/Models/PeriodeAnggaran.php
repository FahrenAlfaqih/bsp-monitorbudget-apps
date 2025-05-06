<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeAnggaran extends Model
{
    use HasFactory;
    protected $table = 'periode_anggaran';
    protected $fillable = ['nama_periode', 'mulai', 'berakhir', 'status'];

    public function rancanganAnggaran()
    {
        return $this->hasMany(RancanganAnggaran::class, 'periode_id');
    }


    public static function autoUpdateStatus()
    {
        DB::statement('CALL UpdateStatusPeriode()');
    }
}
