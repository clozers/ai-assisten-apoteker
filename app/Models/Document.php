<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'content', 'embedding'];
    protected $casts = [
        'embedding' => 'array' // Laravel akan decode JSON otomatis
    ];
}
