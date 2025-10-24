<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;     // Tambahkan ini
use App\Models\Agenda;   // Tambahkan ini

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'judul',
    ];

    // Relasi posts
    public function posts()
    {
        return $this->hasMany(Post::class, 'kategori_id');
    }

    // Relasi agenda
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'kategori_id');
    }
}
