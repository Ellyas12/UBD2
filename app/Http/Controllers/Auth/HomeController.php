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
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;

        $fakultasList = Fakultas::all();
        $lecturers = Dosen::all();

        // --- NEW FILTERS ---
        $fakultas = $request->input('fakultas');
        $jenis = $request->input('jenis');
        $sort = $request->input('sort', 'newest');

        $programList = Program::with(['dosen.fakultas'])
            ->where('status', 'Accepted')
            ->where('stamp', 'Done')
            
            // Filter Fakultas
            ->when($fakultas, function ($q) use ($fakultas) {
                return $q->whereHas('dosen.fakultas', function ($qq) use ($fakultas) {
                    $qq->where('fakultas_id', $fakultas);
                });
            })

            // Filter Jenis
            ->when($jenis, function ($q) use ($jenis) {
                return $q->where('jenis', $jenis);
            })

            // Sort
            ->when($sort === 'newest', fn($q) => $q->orderBy('tanggal', 'desc'))
            ->when($sort === 'oldest', fn($q) => $q->orderBy('tanggal', 'asc'))

            // Pagination (8 per page)
            ->paginate(8)
            ->appends($request->query()); // keep filter params on next pages

        // Your original "My Programs"
        $myPrograms = collect();
        if ($dosen) {
            $myPrograms = Program::with(['dosen', 'pertemuan'])
                ->where('dosen_id', $dosen->dosen_id)
                ->latest('updated_at', 'desc')
                ->get();
        }

        return view('lecturer.home', [
            'user' => $user,
            'dosen' => $dosen,
            'lecturers' => $lecturers,
            'myPrograms' => $myPrograms,
            'programList' => $programList,
            'fakultasList' => $fakultasList
        ]);
    }
}