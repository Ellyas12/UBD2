<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Jabatan;
use App\Models\Program;
use App\Models\Matkul;
use App\Models\Prestasi;
use App\Models\Matdos;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dosen = $user->dosen ?? null;

        // Static lists for dropdowns or options
        $fakultasList = Fakultas::all();
        $jabatanList = Jabatan::all();
        $matkulList = MatKul::all();

        // Initialize collections
        $myMatkul = collect();
        $myPrestasi = collect();
        $programList = collect();

        if ($dosen) {
            // Mata kuliah yang diajar oleh dosen ini
            $myMatkul = Matdos::where('dosen_id', $dosen->dosen_id)
                ->with('matkul')
                ->get()
                ->pluck('matkul');

            // Prestasi milik dosen
            $myPrestasi = Prestasi::where('dosen_id', $dosen->dosen_id)->get();

            // Program yang terkait
            $programList = Program::where('dosen_id', $dosen->dosen_id)
            ->paginate(10)
            ->fragment('research-info');
        }

        return view('lecturer.profile', compact('user','dosen','fakultasList','jabatanList','programList','matkulList','myMatkul','myPrestasi'));
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

    public function updateFakultasJabatan(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,fakultas_id',
            'jabatan_id' => 'required|exists:jabatan,jabatan_id',
        ]);

        $user = auth()->user();
        $dosen = $user->dosen;

        // 🧩 Prevent re-selection
        if ($dosen->fakultas_id || $dosen->jabatan_id) {
            return redirect()->back()->with('error', 'Anda sudah memilih fakultas dan jabatan, tidak dapat diubah lagi.');
        }

        $dosen->update([
            'fakultas_id' => $request->fakultas_id,
            'jabatan_id'  => $request->jabatan_id,
        ]);

        return redirect()->back()->with('success', 'Fakultas dan Jabatan berhasil disimpan!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q'); // must match JS key

        $matkul = MataKuliah::query()
            ->when($query, function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                ->orWhere('kode_matkul', 'like', "%{$query}%");
            })
            ->select('matkul_id', 'kode_matkul', 'nama', 'SKS')
            ->limit(10)
            ->get();

        return response()->json($matkul);
    }

    // #academic-info
    public function assignToDosen(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,dosen_id',
            'matkul_id' => 'required|exists:matkul,matkul_id',
        ]);

        // Check if already assigned to avoid duplicate entries
        $exists = Matdos::where('dosen_id', $request->dosen_id)
            ->where('matkul_id', $request->matkul_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Mata kuliah sudah terdaftar untuk dosen ini.'
            ], 409);
        }

        // Create relation
        Matdos::create([
            'dosen_id' => $request->dosen_id,
            'matkul_id' => $request->matkul_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil ditambahkan ke dosen.'
        ]);
    }

    public function removeMatkul($id)
    {
        $dosen = auth()->user()->dosen;

        if (!$dosen) {
            return back()->with('error', 'Dosen not found.');
        }

        Matdos::where('dosen_id', $dosen->dosen_id)
            ->where('matkul_id', $id)
            ->delete();

        return back()->with('success', 'Mata kuliah berhasil dihapus.');
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'matkul_ids' => 'required|string',
        ]);

        $matkulIds = explode(',', $request->input('matkul_ids'));
        $user = auth()->user();

        // handle both cases safely
        $dosenId = $user->dosen_id ?? ($user->dosen->dosen_id ?? null);

        if (!$dosenId) {
            return redirect()->to(url()->previous() . '#academic-info')
                ->with('error', 'Gagal menambahkan mata kuliah: Dosen ID tidak ditemukan.');
        }

        foreach ($matkulIds as $id) {
            if (!empty($id)) {
                DB::table('matdos')->updateOrInsert([
                    'dosen_id' => $dosenId,
                    'matkul_id' => $id,
                ]);
            }
        }

        return redirect()->to(url()->previous() . '#academic-info')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'Link' => 'required|url',
        ]);

        $user = auth()->user();

        // Handle both cases safely
        $dosenId = $user->dosen_id ?? ($user->dosen->dosen_id ?? null);

        Prestasi::create([
            'user_id' => $user->id,
            'dosen_id' => $dosenId, // add this column if your table supports it
            'nama' => $request->nama,
            'Link' => $request->Link,
        ]);

        return redirect()->to(url()->previous() . '#academic-info')
            ->with('success', 'Prestasi berhasil ditambahkan!');
    }

    // #research-info
    public function viewResearch($id)
    {
        return redirect()->away(route('program.view', $id));
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