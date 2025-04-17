<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    protected $fillable = [
        'id_user',
        'tanggal_lahir',
        'jenis_kelamin',
        'foto_profil',
        'telepon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
