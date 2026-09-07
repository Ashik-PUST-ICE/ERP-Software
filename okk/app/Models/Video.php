<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
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
        'duration',
        'thumbnail_path',
        'status',
        'tenant_id',
    ];

    /**
     * Get the user that owns the video.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}