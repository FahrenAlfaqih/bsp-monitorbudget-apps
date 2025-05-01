<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';

    protected $fillable = ['user_id', 'nama', 'bs_number', 'email',];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spd()
    {
        return $this->hasMany(Spd::class);
    }
}
