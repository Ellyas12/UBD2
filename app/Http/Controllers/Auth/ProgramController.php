<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Pertemuan;
use App\Models\Dosen;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $user = auth()->user();               // logged in user
        $dosen = $user->dosen ?? null;        // fetch related dosen if exists

        $pertemuanList = Pertemuan::all();

        return view('lecturer.program', compact('user', 'dosen', 'pertemuanList'));
    }

    public function store(Request $request)
    {

        $user = auth()->user();
        $dosen = $user->dosen ?? null;

        if (!$dosen) {
            return redirect()->back()->with('error', 'Anda harus melengkapi nama anda di profil dosen terlebih dahulu.');
        }

        $request->validate([
            'jenis'         => 'required|string|max:255',
            'bidang'        => 'required|string|max:255',
            'topik'         => 'required|string|max:255',
            'judul'         => 'required|string|max:255',
            'ketua'         => 'required|string|max:255',
            'anggota'       => 'nullable|string',
            'tanggal'       => 'required|date',
            'biaya'         => 'required|numeric',
            'sumber_biaya'  => 'required|string|max:255',
            'pertemuan_id'  => 'required|exists:pertemuan,pertemuan_id',
            'deskripsi'     => 'nullable|string',
            'linkpdf'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $program = new Program();
        $program->jenis        = $request->jenis;
        $program->bidang       = $request->bidang;
        $program->topik        = $request->topik;
        $program->judul        = $request->judul;
        $program->ketua        = $request->ketua;
        $program->anggota      = $request->anggota;
        $program->tanggal      = $request->tanggal;
        $program->biaya        = $request->biaya;
        $program->sumber_biaya = $request->sumber_biaya;
        $program->pertemuan_id = $request->pertemuan_id;
        $program->deskripsi    = $request->deskripsi;
        $program->dosen_id     = $dosen->dosen_id;

        if ($request->hasFile('linkpdf')) {
        $file = $request->file('linkpdf');
        $filename = time() . '_' . $dosen->dosen_id . '.' . $file->getClientOriginalExtension();

        // save to storage/app/public/program_files
        $file->storeAs('program_files', $filename, 'public');

        // save relative path in DB
        $program->linkpdf = 'program_files/' . $filename;
    }

    $program->save();

    return redirect()->route('program')->with('success', 'Penelitian berhasil ditambahkan!');
    }
}
