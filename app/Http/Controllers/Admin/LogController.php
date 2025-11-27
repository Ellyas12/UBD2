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
        $search = $request->input('search');
        $action = $request->input('action');

        $logs = Log::with('user')
            ->when($action, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.log', [
            'user' => $user,
            'dosen' => $dosen,
            'logs' => $logs,
            'search' => $search,
            'action' => $action,
        ]);
    }
}
