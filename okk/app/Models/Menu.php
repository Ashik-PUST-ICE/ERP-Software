<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'url', 'type', 'status'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'url', 'id');
    }

    public function getPageAttribute()
    {
        return $this->pageRelation;
    }

    public function pageRelation()
    {
        return $this->belongsTo(Page::class, 'url', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

}