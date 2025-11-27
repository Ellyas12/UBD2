<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Stamp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProgramController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $dosen = $authUser->dosen ?? null;
        $search = $request->input('search');

        $programs = Program::with(['files', 'dosen'])
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', "%$search%")
                      ->orWhere('jenis', 'like', "%$search%")
                      ->orWhere('bidang', 'like', "%$search%")
                      ->orWhere('status', 'like', "%$search%")
                      ->orWhere('stamp', 'like', "%$search%")
                      ->orWhereHas('dosen', function ($q) use ($search) {
                          $q->where('nama', 'like', "%$search%");
                      });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.program', [
            'user' => $authUser,
            'dosen' => $dosen,
            'programs' => $programs,
            'search' => $search,
        ]);
    }

    public function view($id)
    {
        $program = Program::with(['dosen', 'pertemuan'])->findOrFail($id);
        $files = \App\Models\File::where('program_id', $id)->get();

        return view('admin.program-view', compact('program', 'files'));
    }

    public function edit($id)
    {
        $authUser = Auth::user();
        $dosen = $authUser->dosen ?? null;

        $program = Program::with(['files', 'stampRecord', 'dosen'])->findOrFail($id);

        // ✅ Only Dosen with posisi = 'Kaprodi' can stamp
        $dosenList = Dosen::whereHas('user', function ($query) {
            $query->where('posisi', 'Kaprodi');
        })->get();

        return view('admin.program-edit', [
            'user' => $authUser,
            'dosen' => $dosen,
            'program' => $program,
            'dosenList' => $dosenList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Accepted,Denied,Revisi',
            'stamp' => 'required|in:Done,Not yet',
            'stamped_dosen' => 'nullable|integer|exists:dosen,dosen_id',
        ]);

        $program = Program::findOrFail($id);

        // Enforce rule: if stamp = Not yet → status must be Pending or Revisi
        if ($request->stamp === 'Not yet' && !in_array($request->status, ['Pending', 'Revisi'])) {
            return back()->withErrors([
                'status' => 'When stamp is "Not yet", status can only be Pending or Revisi.',
            ]);
        }

        $program->status = $request->status;
        $program->stamp = $request->stamp;
        $program->save();

        // ✅ Handle stamp logic
        if ($request->stamp === 'Done') {
            // Only allow Kaprodi
            $kaprodi = Dosen::where('dosen_id', $request->stamped_dosen)
                ->whereHas('user', function ($query) {
                    $query->where('posisi', 'Kaprodi');
                })
                ->first();
            if (!$kaprodi) {
                return back()->withErrors([
                    'stamped_dosen' => 'Only a Dosen with posisi "Kaprodi" can stamp this program.',
                ]);
            }

            Stamp::updateOrCreate(
                ['program_id' => $program->program_id],
                ['dosen_id' => $kaprodi->dosen_id]
            );
        } else {
            Stamp::where('program_id', $program->program_id)->delete();
        }

        return redirect()->route('admin.programs')->with('success', 'Program updated successfully.');
    }
}
