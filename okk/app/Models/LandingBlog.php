<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingBlog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'url',
        'image',
        'status',
    ];
}

