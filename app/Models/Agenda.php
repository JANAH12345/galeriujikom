<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kategori;

class Agenda extends Model
{
    use HasFactory;

    protected $table = 'agenda';

    protected $fillable = [
        'judul',
        'kategori_id',
    ];

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
