<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Dosen;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\Fakultas;

class UserController extends Controller
{
    public function index(Request $request)
    {
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

        $userData = User::with('dosen')->findOrFail($id);

        // Load dropdown data
        $jabatanList = Jabatan::all();
        $fakultasList = Fakultas::all();

        return view('admin.user-edit', [
            'user' => $authUser,
            'dosen' => $dosen,
            'userData' => $userData,
            'jabatanList' => $jabatanList,
            'fakultasList' => $fakultasList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nidn' => 'nullable|string|max:50',
            'posisi' => 'required|in:Dekan,Kaprodi,Guru',
            'role' => 'required|in:Lecturer,Admin',

            // Dosen fields
            'nama' => 'nullable|string|max:100',
            'telp' => 'nullable|string|max:20',
            'pendidikan' => 'nullable|string|max:40',
            'bidang' => 'nullable|string|max:40',
            'jabatan_id' => 'nullable|integer',
            'fakultas_id' => 'nullable|integer',
        ]);

        $user = User::findOrFail($id);

        // Update user
        $user->username = $request->username;
        $user->email = $request->email;
        $user->nidn = $request->nidn;
        $user->posisi = $request->posisi;
        $user->role = $request->role;
        $user->save();

        // If this user has a dosen detail, update it
        if ($user->dosen) {
            $user->dosen->nama = $request->nama;
            $user->dosen->telp = $request->telp;
            $user->dosen->pendidikan = $request->pendidikan;
            $user->dosen->bidang = $request->bidang;
            $user->dosen->jabatan_id = $request->jabatan_id;
            $user->dosen->fakultas_id = $request->fakultas_id;
            $user->dosen->save();
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

}