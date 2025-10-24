<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'foto';

    protected $fillable = [
        'gallery_id',
        'file',
        'judul',
    ];

    public function gallery() {
        return $this->belongsTo(Gallery::class);
    }
}
