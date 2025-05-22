<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel periode_anggaran.
 *
 * @category Model
 * @package  App\Models
 * @author   Fahren Al Faqih <fahren66@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/FahrenAlFaqih/bsp-final-project.git
 *
 * Kelas ini digunakan untuk berinteraksi dengan tabel periode_anggaran pada database.
 */
class PeriodeAnggaran extends Model
{
    use HasFactory;
    protected $table = 'periode_anggaran';
    protected $fillable = ['nama_periode', 'mulai', 'berakhir', 'status', 'user_id'];

    /**
     * @desc Fungsi ini membuat relasi one to many ke model Rancangan Anggaran dengan periode id menjadi FK ke table 
     * rancangan anggaran
     *
     * @return void
     */
    public function rancanganAnggaran()
    {
        return $this->hasMany(RancanganAnggaran::class, 'periode_id');
    }


    /**
     * @desc Fungsi ini memanggil storedProcedure() yang telah dibuat dengan query mysql, tujuan nya untuk merubah status periode
     * dari table periode_anggaran menjadi ditutup ketika tanggal berakhir < CURRENT DATE()
     *
     * @return void
     */
    public static function autoUpdateStatus()
    {
        DB::statement('CALL UpdateStatusPeriode()');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
