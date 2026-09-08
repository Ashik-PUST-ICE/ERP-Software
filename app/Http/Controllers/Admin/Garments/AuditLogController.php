<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->string('module')))
            ->when($request->filled('action'), fn ($query) => $query->where('action', $request->string('action')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.garments.audit-logs.index', [
            'title' => __('Garments Audit Log'),
            'logs' => $logs,
            'activeGarments' => 'active',
            'activeGarmentAuditLogs' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
