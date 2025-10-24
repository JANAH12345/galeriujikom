<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'post_id',
        'position',
        'status',
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function foto() {
        return $this->hasMany(Foto::class);
    }
}
