<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'category',
        'views',
        'published_at',
        'author',
    ];
    
    protected $casts = [
        'published_at' => 'date',
        'views' => 'integer',
    ];
}
