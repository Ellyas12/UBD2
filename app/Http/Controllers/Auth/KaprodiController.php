<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Stamp;
use Illuminate\Http\Request;

class KaprodiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $kaprodi = Dosen::where('user_id', $user->user_id)->first();

        if (!$kaprodi) {
            return redirect()->route('lecturer.home')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Search inputs
        $searchUnstamped = $request->search_unstamped;
        $searchStamped   = $request->search_stamped;

        $unstamped = Program::whereHas('dosen', function ($query) use ($kaprodi) {
                $query->where('fakultas_id', $kaprodi->fakultas_id);
            })
            ->where('stamp', '!=', 'Done')
            ->when($searchUnstamped, function ($q) use ($searchUnstamped) {
                $q->where('judul', 'like', "%{$searchUnstamped}%");
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->paginate(5, ['*'], 'unstamped_page');

        $stamped = Program::whereHas('dosen', function ($query) use ($kaprodi) {
                $query->where('fakultas_id', $kaprodi->fakultas_id);
            })
            ->where('stamp', 'Done')
            ->when($searchStamped, function ($q) use ($searchStamped) {
                $q->where('judul', 'like', "%{$searchStamped}%");
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->paginate(5, ['*'], 'stamped_page');

        return view('lecturer.kaprodi', compact('unstamped', 'stamped'));
    }

    public function showStampPage($program_id)
    {
        $program = Program::with(['dosen', 'pertemuan'])->findOrFail($program_id);
        $files = \App\Models\File::where('program_id', $program_id)->get();
        return view('lecturer.kaprodi-kesah', compact('program', 'files'));
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