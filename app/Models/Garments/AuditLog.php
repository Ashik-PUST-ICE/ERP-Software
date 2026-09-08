<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'garment_audit_logs';
    protected $fillable = ['user_id', 'action', 'module', 'record_id', 'description', 'changes', 'ip_address'];
    protected $casts = ['changes' => 'array'];
}
