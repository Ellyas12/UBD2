<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProgramController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;

        return view('admin.program', [
            'user' => $user,
            'dosen' => $dosen,
        ]);
    }
}
