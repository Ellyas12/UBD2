<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Dosen;
use App\Models\Comment;
use Illuminate\Http\Request;

class DekanController extends Controller
{
    /**
     * Display the main Dekan dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $dekan = Dosen::where('user_id', $user->user_id)->first();

        if (!$dekan) {
            return redirect()->route('lecturer.home')->with('error', 'Data dosen tidak ditemukan.');
        }

        // Only show programs that have already been stamped (stamp = 'Done')
        $programs = Program::where('stamp', 'Done')
            ->whereHas('dosen', function ($query) use ($dekan) {
                $query->where('fakultas_id', $dekan->fakultas_id);
            })
            ->with('dosen')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Separate programs based on their review status
        $pending = $programs->where('status', 'Pending');
        $processed = $programs->whereIn('status', ['Accepted', 'Denied', 'Revisi']);

        return view('lecturer.dekan', compact('pending', 'processed'));
    }

    /**
     * Show the review form for a specific program.
     */
    public function showReviewPage($program_id)
    {
        $program = Program::with('dosen')->findOrFail($program_id);

        // Ensure only stamped programs can be reviewed
        if ($program->stamp !== 'Done') {
            return redirect()->route('dekan')->with('error', 'Program ini belum di-stamp oleh Kaprodi.');
        }

        return view('lecturer.dekan-terima', compact('program'));
    }

    /**
     * Handle review submission: update program status & add comment.
     */
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

        // Only allow review if program has been stamped
        if ($program->stamp !== 'Done') {
            return redirect()->route('dekan')->with('error', 'Program ini belum di-stamp oleh Kaprodi.');
        }

        // Update the program's review status
        $program->update(['status' => $request->status]);

        // Create a comment record for this review
        Comment::create([
            'program_id' => $program->program_id,
            'dosen_id'   => $dosen->dosen_id,
            'content'    => $request->content,
        ]);

        return redirect()->route('dekan')->with('success', 'Review berhasil dikirim dan status program diperbarui.');
    }
}
