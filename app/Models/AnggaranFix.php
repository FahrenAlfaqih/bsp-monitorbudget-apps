<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel anggaran_fix.
 *
 * @category Model
 * @package  App\Models
 * @author   Fahren Al Faqih <fahren66@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/FahrenAlFaqih/bsp-final-project.git
 *
 * Kelas ini digunakan untuk berinteraksi dengan tabel anggaran_fix pada database.
 */
class AnggaranFix extends Model
{
    use HasFactory;

    protected $table = "anggaran_fix";

    protected $fillable = [
        'departemen_id',
        'periode_id',
        'jumlah_anggaran',
    ];
}
