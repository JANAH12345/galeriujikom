<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // untuk auth
// use Laravel\Sanctum\HasApiTokens; // tidak dipakai

class Petugas extends Authenticatable
{
    use HasFactory; // hapus HasApiTokens

    protected $table = 'petugas';

    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Hash password otomatis ketika diset
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = bcrypt($value);
    }

    // Relasi posts
    public function posts() {
        return $this->hasMany(Post::class);
    }

    // Relasi agenda
    public function agendas() {
        return $this->hasMany(Agenda::class);
    }
}
