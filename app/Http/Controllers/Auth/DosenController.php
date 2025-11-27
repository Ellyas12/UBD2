<?php

namespace App\Http\Controllers\Auth;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Program;
use App\Models\Prestasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function show($id)
    {
        $dosen = Dosen::with(['fakultas', 'jabatan', 'program', 'user'])->findOrFail($id);
        $myPrestasi = Prestasi::where('dosen_id', $dosen->dosen_id)->get();
        $programList = Program::where('dosen_id', $dosen->dosen_id)
            ->paginate(10)
            ->fragment('research-info');
        return view('lecturer.dosen-profile', compact('dosen', 'myPrestasi', 'programList'));
    }
}