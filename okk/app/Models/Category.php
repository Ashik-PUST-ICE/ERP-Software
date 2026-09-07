<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'short_description',
        'status',
        'created_by',
        'tenant_id',
    ];

    public function templates()
    {
        return $this->hasMany(Template::class, 'category_id');
    }

    /**
     * Get the user that created the category.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}