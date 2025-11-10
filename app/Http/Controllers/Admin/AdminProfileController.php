<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;

        return view('admin.profile', [
            'user' => $user,
            'dosen' => $dosen,
        ]);
    }
}
