<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dosen = $user->dosen ?? null;
        $announcement = Announcement::firstOrCreate(['announcement_id' => 1]);


        return view('admin.announcement', [
            'user' => $user,
            'dosen' => $dosen,
            'announcement' => $announcement,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $announcement = Announcement::firstOrCreate(['announcement_id' => 1]);
        $announcement->update($request->only(['title', 'body']));

        return redirect()->back()->with('success', 'Announcement updated.');
    }
}

