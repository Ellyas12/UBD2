<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $dosen = $user->dosen ?? null;

        $query = Log::query();

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.log', [
            'user' => $user,
            'dosen' => $dosen,
            'logs' => $logs, // ✅ send logs to blade
        ]);
    }
}
