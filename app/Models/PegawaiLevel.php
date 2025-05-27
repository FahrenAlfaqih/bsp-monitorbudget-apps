<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PegawaiLevel extends Model
{
    protected $fillable = ['kode_level', 'nama_level'];

    public function reimburseRules()
    {
        return $this->hasMany(ReimburseRule::class);
    }
}
