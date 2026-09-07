<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'title',
        'platform',
        'file_name',
        'file_path',
        'file_type',
        'file_extension',
        'file_size',
        'dimensions',
        'tenant_id',
    ];

    /**
     * Get the user that owns the gallery.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}