<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = AuditLog::with('user');

        // Filter action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter model
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(50);

        $actions = AuditLog::distinct()->pluck('action');
        $models = AuditLog::distinct()->pluck('model_type');

        return view('audit-log.index', compact('auditLogs', 'actions', 'models'));
    }
}
