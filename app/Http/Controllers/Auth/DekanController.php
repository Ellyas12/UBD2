<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Comment;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dekan = Dosen::where('user_id', $user->user_id)->first();

        if (!$dekan) {
            return redirect()->route('lecturer.home')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        $searchPending   = $request->search_pending;
        $searchRevision  = $request->search_revision;
        $searchProcessed = $request->search_processed;

        $pending = Program::whereHas('dosen', function ($q) use ($dekan) {
                $q->where('fakultas_id', $dekan->fakultas_id);
            })
            ->where('stamp', 'Done')
            ->where('status', 'Pending')
            ->when($searchPending, function ($q) use ($searchPending) {
                $q->where('judul', 'like', "%{$searchPending}%");
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->paginate(5, ['*'], 'pending_page');

        $revision = Program::whereHas('dosen', function ($q) use ($dekan) {
                $q->where('fakultas_id', $dekan->fakultas_id);
            })
            ->where('status', 'Revisi')
            ->when($searchRevision, function ($q) use ($searchRevision) {
                $q->where('judul', 'like', "%{$searchRevision}%");
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->paginate(5, ['*'], 'revision_page');

        $processed = Program::whereHas('dosen', function ($q) use ($dekan) {
                $q->where('fakultas_id', $dekan->fakultas_id);
            })
            ->where('stamp', 'Done')
            ->whereIn('status', ['Accepted', 'Denied'])
            ->when($searchProcessed, function ($q) use ($searchProcessed) {
                $q->where('judul', 'like', "%{$searchProcessed}%");
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->paginate(5, ['*'], 'processed_page');

        return view('lecturer.dekan', compact('pending', 'revision', 'processed'));
    }

    public function showReviewPage($program_id)
    {
        $program = Program::with('dosen')->findOrFail($program_id);
        $isEditable = ($program->stamp === 'Done' && $program->status !== 'Accepted');
        $files = \App\Models\File::where('program_id', $program_id)->get();
        return view('lecturer.dekan-terima', compact('program', 'isEditable', 'files'));
    }

    public function submitReview(Request $request, $program_id)
    {
        $request->validate([
            'status'  => 'required|in:Accepted,Denied,Revisi',
            'content' => 'required|string|max:1000',
        ]);

        $program = Program::findOrFail($program_id);
        $user = auth()->user();
        $dosen = Dosen::where('user_id', $user->user_id)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        if ($program->stamp !== 'Done' || $program->status === 'Accepted') {
            return redirect()->route('dekan')->with('error', 'Program tidak dapat direview.');
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'Revisi') {
            $updateData['stamp'] = 'Not Yet';
        }

        $program->update($updateData);

        $existingComment = Comment::where('program_id', $program->program_id)
            ->where('dosen_id', $dosen->dosen_id)
            ->first();

        if ($existingComment) {
            $existingComment->update([
                'content' => $request->content,
            ]);
        } else {
            Comment::create([
                'program_id' => $program->program_id,
                'dosen_id'   => $dosen->dosen_id,
                'content'    => $request->content,
            ]);
        }
        return redirect()->route('dekan')->with('success', 'Review berhasil dikirim dan status program diperbarui.');
    }
}
