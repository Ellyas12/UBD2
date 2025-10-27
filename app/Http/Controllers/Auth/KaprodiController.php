<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Stamp;
use Illuminate\Http\Request;

class KaprodiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $kaprodi = Dosen::where('user_id', $user->user_id)->first();

        if (!$kaprodi) {
            return redirect()->route('lecturer.home')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Programs under the same faculty as this Kaprodi
        $programs = Program::whereHas('dosen', function ($query) use ($kaprodi) {
            $query->where('fakultas_id', $kaprodi->fakultas_id);
        })
        ->with('dosen')
        ->orderBy('tanggal', 'desc')
        ->get();

        // Separate them based on stamp status
        $unstamped = $programs->where('stamp', '!=', 'Done');
        $stamped = $programs->where('stamp', 'Done');

        return view('lecturer.kaprodi', compact('unstamped', 'stamped'));
    }

    public function showStampPage($program_id)
    {
        $program = Program::with('dosen')->findOrFail($program_id);
        return view('lecturer.kaprodi-kesah', compact('program'));
    }

    public function confirmStamp($program_id)
    {
        $program = Program::findOrFail($program_id);
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->user_id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        if ($program->stamp == 'Done') {
            return redirect()->route('kaprodi')->with('warning', 'Program sudah di-stamp sebelumnya.');
        }

        $program->stamp = 'Done';
        $program->save();

        Stamp::create([
            'program_id' => $program->program_id,
            'dosen_id' => $dosen->dosen_id,
        ]);

        return redirect()->route('kaprodi')->with('success', 'Program berhasil di-stamp.');
    }
}