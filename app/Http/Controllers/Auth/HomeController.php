<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Fakultas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;
        $fakultasList = Fakultas::all();
        $lecturers = Dosen::all();

        $programList = Program::where('status', 'Accepted')
            ->where('stamp', 'Done')
            ->get();


        $myPrograms = [];
        if ($dosen) {
            $myPrograms = Program::with(['dosen', 'pertemuan'])
                ->where('dosen_id', $dosen->dosen_id)
                ->latest('tanggal')
                ->get();
        }

        $recentPrograms = Program::with(['dosen', 'pertemuan'])
            ->latest('tanggal')
            ->take(6)
            ->get();

        return view('lecturer.home', [
            'user' => $user,
            'dosen' => $dosen,
            'lecturers' => $lecturers,
            'myPrograms' => $myPrograms,
            'recentPrograms' => $recentPrograms,
            'programList' => $programList,
            'fakultasList' => $fakultasList
        ]);
    }
}