<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename', 'url', 'name', 'email', 'role', 'purpose', 'ip'
    ];
}
