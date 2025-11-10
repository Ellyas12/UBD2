<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;

        return view('admin.announcement', [
            'user' => $user,
            'dosen' => $dosen,
        ]);
    }
}

