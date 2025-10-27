<?php

namespace App\Http\Controllers\Auth;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Program;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function show($id)
    {
        $dosen = Dosen::with(['fakultas', 'jabatan', 'program', 'user'])->findOrFail($id);
        return view('lecturer.dosen-profile', compact('dosen'));
    }
}