<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();               // logged in user
        $dosen = $user->dosen ?? null;        // fetch related dosen if exists

        $fakultasList = Fakultas::all();
        $jabatanList = Jabatan::all();
        $programList = collect(); // default empty list

        if ($dosen) {
            $programList = Program::where('dosen_id', $dosen->dosen_id)->get();
        }

        return view('lecturer.profile', compact('user', 'dosen', 'fakultasList', 'jabatanList', 'programList'));
    }

    // #personal-info
    public function update(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'fakultas_id'       => 'nullable|integer|exists:fakultas,fakultas_id',
            'jabatan_id'        => 'nullable|integer|exists:jabatan,jabatan_id',
            'email'             => 'nullable|email',
            'telepon'           => 'nullable|string|max:20',
            'pendidikan'        => 'nullable|string|max:40',
            'bidang'            => 'nullable|string|max:40',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($request->filled('email')) {
            $user->email = $request->email;
            $user->save();
        }

        $dosen = Dosen::firstOrNew(['user_id' => $user->user_id]);

        $dosen->nama        = $request->nama;
        $dosen->fakultas_id = $request->fakultas_id;
        $dosen->jabatan_id  = $request->jabatan_id;
        $dosen->telp        = $request->telepon;
        $dosen->pendidikan  = $request->pendidikan;
        $dosen->bidang      = $request->bidang;

        if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');

            // delete old file if exists
            if ($dosen->profile_picture && Storage::disk('public')->exists($dosen->profile_picture)) {
                Storage::disk('public')->delete($dosen->profile_picture);
            }

            $filename = time() . '_' . $user->user_id . '.' . $file->getClientOriginalExtension();

                // save into storage/app/public/profile_pictures
                $file->storeAs('profile_pictures', $filename, 'public');

                // save relative path (no "storage/" prefix here)
                $dosen->profile_picture = 'profile_pictures/' . $filename;
            }

        $dosen->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function removePicture(Request $request)
    {
        $user = auth()->user();
        $dosen = $user->dosen;

        if ($dosen && $dosen->profile_picture) {
            if (Storage::disk('public')->exists($dosen->profile_picture)) {
                Storage::disk('public')->delete($dosen->profile_picture);
            }

            $dosen->profile_picture = null;
            $dosen->save();
        }

        return back()->with('success', 'Profile picture removed successfully!');
    }

    // #research-info
    public function viewResearch($id)
    {
        return redirect()->away(route('program.view', $id));
    }

    public function editResearch($id)
    {
        return redirect()->away(route('program.edit', $id));
    }


    // #security-info
    public function sendSecurityCode(Request $request)
    {
        $user = auth()->user();

        $code = rand(100000, 999999);

        DB::table('preset')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $code, 'created_at' => now()]
        );

        Mail::raw("Your Security reset code is: $code", function ($message) use ($user) {
            $message->to($user->email)->subject('Security Reset Code');
        });

        return redirect()->to(url()->previous() . '#security-info')
        ->with('success', 'Verification code sent!')
        ->with('showVerify', true);
    }

    public function verifySecurityCode(Request $request)
    {
        $user = auth()->user();
        $request->validate(['code' => 'required']);

        $record = DB::table('preset')->where('email', $user->email)->first();

        if (!$record) {
            return redirect()->to(url()->previous() . '#security-info')
                ->with('error', 'No code found for this email. Please request a new code.')
                ->with('showVerify', true); // 👈 keep verify form open
        }

        if (Carbon::now()->gt(Carbon::parse($record->created_at)->addSeconds(60))) {
            return redirect()->to(url()->previous() . '#security-info')
                ->with('error', 'Code has expired. Please request a new code.')
                ->with('showVerify', true); // 👈 keep verify form open
        }

        if ($request->code != $record->token) {
            return redirect()->to(url()->previous() . '#security-info')
                ->with('error', 'Invalid code.')
                ->with('showVerify', true); // 👈 keep verify form open
        }

        // success
        DB::table('preset')->where('email', $user->email)->delete();

        return redirect()->to(url()->previous() . '#security-info')
            ->with('success', 'Code verified successfully!')
            ->with('showUpdate', true);
    }

    public function updateSecurity(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6|confirmed',
            'email'    => 'nullable|email|unique:users,email,' . $user->user_id . ',user_id',
            'nidn'     => 'nullable|string|max:50|unique:users,nidn,' . $user->user_id . ',user_id',
        ]);

        // Update only if field is filled
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->filled('nidn')) {
            $user->nidn = $request->nidn;
        }

        if ($request->filled('email') && $request->email !== $user->email) {
            $user->email = $request->email;
        }

        $user->save();

        \Auth::login($user);

        return redirect()->to(url()->previous() . '#security-info')
            ->with('success', 'Security information updated successfully!');
    }
}