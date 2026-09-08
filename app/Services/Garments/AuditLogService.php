<?php

namespace App\Services\Garments;

use App\Models\Garments\AuditLog;

class AuditLogService
{
    public function record(string $action, string $module, ?int $recordId, string $description, ?array $changes = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
