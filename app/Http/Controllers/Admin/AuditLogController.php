<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->event) {
            $query->where('event', $request->event);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->auditable_type) {
            $query->where('auditable_type', 'like', '%' . $request->auditable_type . '%');
        }

        $logs = $query->paginate(20);

        return view('admin.audit-logs.index', compact('logs'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
