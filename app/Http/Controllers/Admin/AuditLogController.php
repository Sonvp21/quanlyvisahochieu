<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $q = AuditLog::with(['user:id,name', 'trip:id,code'])->latest('id');

        if ($request->filled('user_id')) {
            $q->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $q->where('action', $request->action);
        }
        if ($s = trim((string) $request->get('q'))) {
            $q->where('summary', 'ilike', "%{$s}%");
        }
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->to);
        }

        return view('admin.audit.index', [
            'logs'  => $q->paginate(40)->withQueryString(),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ]);
    }
}
