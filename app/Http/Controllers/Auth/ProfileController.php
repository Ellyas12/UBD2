<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jabatan;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();               // logged in user
        $dosen = $user->dosen ?? null;        // fetch related dosen if exists

        
        $fakultasList = Fakultas::all();
        $jabatanList = Jabatan::all();


        return view('lecturer.profile', compact('user', 'dosen', 'fakultasList', 'jabatanList'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'fakultas' => 'nullable|string|max:255',
            'jabatan'  => 'nullable|string|max:255',
            'email'    => 'nullable|email',
            'telepon'  => 'nullable|string|max:20',
        ]);

        $user = auth()->user();

        Dosen::updateOrCreate(
            ['user_id' => $user->id], 
            $request->only(['nama', 'fakultas', 'jabatan', 'email', 'telepon'])
        );

        return back()->with('success', 'Profile updated successfully!');
    }
}