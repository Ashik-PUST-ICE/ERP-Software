<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'slug',
        'status',
        'tenant_id',
    ];
}
