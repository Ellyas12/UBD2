<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Keep the authenticated user
        $authUser = Auth::user();
        $dosen = $authUser->dosen ?? null;

        $search = $request->input('search');

        // User + Dosen Joined Query with Search
        $users = User::with('dosen')
            ->when($search, function ($query, $search) {
                $query->where('username', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('nidn', 'like', "%$search%")
                      ->orWhereHas('dosen', function ($q) use ($search) {
                          $q->where('nama', 'like', "%$search%");
                      });
            })
            ->get();

        return view('admin.user', [
            'user' => $authUser,   // keep original
            'dosen' => $dosen,     // keep original
            'users' => $users,     // new user list
            'search' => $search    // search value
        ]);
    }

    public function edit($id)
    {
        $authUser = Auth::user();
        $dosen = $authUser->dosen ?? null;

        // Load the user and dosen relation
        $user = User::with('dosen')->findOrFail($id);

        return view('admin.user-edit', [
            'userData' => $user,   // editing target
            'user' => $authUser,   // logged-in admin
            'dosen' => $dosen,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nidn' => 'nullable|string|max:50',
            'role' => 'required|string',
        ]);

        // Update User
        $user = User::findOrFail($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->nidn = $request->nidn;
        $user->role = $request->role;
        $user->save();

        if ($user->dosen) {
            $user->dosen->nama = $request->nama;
            $user->dosen->telp = $request->telp;
            $user->dosen->pendidikan = $request->pendidikan;
            $user->dosen->bidang = $request->bidang;
            $user->dosen->save();
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }
}