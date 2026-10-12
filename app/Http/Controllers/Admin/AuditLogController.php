<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->latest('id');

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->query('action') . '%');
        }

        if ($request->filled('actor')) {
            $query->where('user_email', 'like', '%' . $request->query('actor') . '%');
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.audit.index', compact('logs'));
    }
}
