<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Pertemuan;
use App\Models\Dosen;
use App\Models\Anggota;
use App\Models\Ketua;
use App\Models\File;
use App\Models\ProgramBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dosen = $user->dosen ?? null;

        $pertemuanList = Pertemuan::all();
        $programList = Program::all();

        // Pagination for main Program list (same as home.blade behavior)
        $programList = Program::with(['dosen', 'pertemuan'])
            ->orderBy('updated_at', 'desc')
            ->paginate(5)
            ->appends($request->query());

        // My Programs — NOT paginated (same as home.blade)
        $myPrograms = collect();
        if ($dosen) {
            $myPrograms = Program::with(['dosen', 'pertemuan', 'ketua.dosen', 'anggota.dosen'])
                ->where('dosen_id', $dosen->dosen_id)
                ->orderBy('updated_at', 'desc')
                ->get();   // ← collection, not paginator
        }

        return view('lecturer.program', compact(
            'user', 'dosen', 'pertemuanList', 'programList', 'myPrograms'
        ));
    }

    public function createProgram()
    {
        $pertemuanList = Pertemuan::all();
        $dosenList = Dosen::all();
        return view('lecturer.program-create', compact('pertemuanList', 'dosenList'));
    }

    public function searchDosen(Request $request)
    {
        $query = $request->get('q', '');
        $excludeIds = $request->get('exclude', []); // array of dosen_id to exclude

        $results = Dosen::join('users', 'dosen.user_id', '=', 'users.user_id')
                        ->where(function ($q2) use ($query) {
                            $q2->where('dosen.nama', 'like', "%{$query}%")
                            ->orWhere('users.nidn', 'like', "%{$query}%");
                        })
                        ->when(!empty($excludeIds), function ($q2) use ($excludeIds) {
                            $q2->whereNotIn('dosen.dosen_id', $excludeIds);
                        })
                        ->limit(20)
                        ->get([
                            'dosen.dosen_id',
                            'dosen.nama',
                            'users.nidn',
                        ]);

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $dosen = $user->dosen ?? null;

        if (!$dosen) {
            return redirect()->back()
                ->with('error', 'Anda harus melengkapi profil dosen terlebih dahulu.')
                ->withInput();
        }

        /* ------------------------------------------------------
        | 1. Clean biaya format (convert "1.500.000" -> "1500000")
        ------------------------------------------------------ */
        if ($request->filled('biaya')) {
            $request->merge([
                'biaya' => str_replace('.', '', $request->biaya)
            ]);
        }

        /* ------------------------------------------------------
        | 2. Validate inputs
        ------------------------------------------------------ */
        $request->validate([
            'jenis'         => 'required|string|max:255',
            'bidang'        => 'required|string|max:255',
            'topik'         => 'required|string|max:255',
            'judul'         => 'required|string|max:255',
            'ketua_id'      => 'required|exists:dosen,dosen_id',
            'anggota_ids'   => 'nullable|array',
            'anggota_ids.*' => 'exists:dosen,dosen_id',
            'tanggal'       => 'required|date',
            'biaya'         => 'required|numeric',
            'sumber_biaya'  => 'required|string|max:255',
            'pertemuan_id'  => 'required|exists:pertemuan,pertemuan_id',
            'deskripsi'     => 'nullable|string',
            'linkweb'       => 'nullable|string',
            'linkpdf'       => 'nullable|array|max:5',
            'linkpdf.*'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:5120',
        ]);

        /* ------------------------------------------------------
        | 3. Check total file size ≤ 10MB
        ------------------------------------------------------ */
        $totalSize = 0;
        if ($request->hasFile('linkpdf')) {
            foreach ($request->file('linkpdf') as $file) {
                $totalSize += $file->getSize();
            }
        }

        if ($totalSize > 10 * 1024 * 1024) {
            return back()->with('error', 'Total ukuran semua file tidak boleh melebihi 10MB.')
                        ->withInput();
        }

        /* ------------------------------------------------------
        | 4. Create Program
        ------------------------------------------------------ */
        $program = Program::create([
            'jenis'        => $request->jenis,
            'bidang'       => $request->bidang,
            'topik'        => $request->topik,
            'judul'        => $request->judul,
            'tanggal'      => $request->tanggal,
            'biaya'        => (int) $request->biaya, // already cleaned
            'sumber_biaya' => $request->sumber_biaya,
            'pertemuan_id' => $request->pertemuan_id,
            'linkweb'      => $request->linkweb,
            'deskripsi'    => $request->deskripsi,
            'dosen_id'     => $dosen->dosen_id,
        ]);

        /* ------------------------------------------------------
        | 5. Save Ketua (leader)
        ------------------------------------------------------ */
        Ketua::create([
            'program_id' => $program->program_id,
            'dosen_id'   => $request->ketua_id,
        ]);

        /* ------------------------------------------------------
        | 6. Save Anggota (members)
        ------------------------------------------------------ */
        if ($request->filled('anggota_ids')) {
            foreach ($request->anggota_ids as $anggotaId) {
                Anggota::create([
                    'program_id' => $program->program_id,
                    'dosen_id'   => $anggotaId,
                ]);
            }
        }

        /* ------------------------------------------------------
        | 7. Upload files
        ------------------------------------------------------ */
        if ($request->hasFile('linkpdf')) {
            $folderPath = 'program_files/' . $program->program_id;

            foreach ($request->file('linkpdf') as $uploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

                $uploadedFile->storeAs($folderPath, $filename, 'public');

                File::create([
                    'program_id' => $program->program_id,
                    'nama'       => $uploadedFile->getClientOriginalName(),
                    'file'       => $folderPath . '/' . $filename,
                    'folder'     => $folderPath,
                ]);
            }
        }

        return redirect()->route('program')
            ->with('success', 'Program, ketua, dan anggota berhasil ditambahkan!');
    }


    public function view($id)
    {
        $program = Program::with(['dosen', 'pertemuan'])->findOrFail($id);
        $files = \App\Models\File::where('program_id', $id)->get();

        return view('lecturer.program-view', compact('program', 'files'));
    }

    public function edit($id)
    {
        $program = Program::with('files', 'ketua.dosen', 'anggota.dosen')->findOrFail($id);
        $pertemuanList = Pertemuan::all();
        $dosenList = Dosen::all();

        return view('lecturer.program-edit', compact('program', 'pertemuanList', 'dosenList'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'jenis'         => 'required|string|max:255',
            'bidang'        => 'required|string|max:255',
            'topik'         => 'required|string|max:255',
            'judul'         => 'required|string|max:255',
            'ketua_id'      => 'required|exists:dosen,dosen_id',
            'anggota_ids'   => 'nullable|array',
            'anggota_ids.*' => 'exists:dosen,dosen_id',
            'tanggal'       => 'required|date',
            'biaya'         => 'required|numeric',
            'sumber_biaya'  => 'required|string|max:255',
            'pertemuan_id'  => 'required|exists:pertemuan,pertemuan_id',
            'linkweb'       => 'nullable|string',
            'deskripsi'     => 'nullable|string',
            'linkpdf.*'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:5120',
        ]);

        // ✅ Save Ketua (leader)
        Ketua::updateOrCreate([
            'program_id' => $program->program_id,
            'dosen_id'   => $request->ketua_id,
        ]);

        Anggota::where('program_id', $program->program_id)->delete(); // clear old anggota
        if ($request->anggota_ids) {
            foreach ($request->anggota_ids as $anggotaId) {
                Anggota::create([
                    'program_id' => $program->program_id,
                    'dosen_id'   => $anggotaId,
                ]);
            }
        }

        // ✅ Update program info
        $program->update($request->only([
            'jenis', 'bidang', 'topik', 'judul', 'ketua_id', 'anggota_ids', 
            'tanggal', 'biaya', 'sumber_biaya', 'pertemuan_id', 'linkweb', 'deskripsi'
        ]));

        // ✅ Handle file deletions (from hidden input)
        if ($request->filled('deleted_files')) {
            $deletedFileIds = explode(',', $request->deleted_files);
            $filesToDelete = File::whereIn('file_id', $deletedFileIds)->get();

            foreach ($filesToDelete as $file) {
                if (Storage::disk('public')->exists($file->file)) {
                    Storage::disk('public')->delete($file->file);
                }
                $file->delete();
            }
        }

        // ✅ Handle new file uploads
        if ($request->hasFile('linkpdf')) {
            $folderPath = 'program_files/' . $program->program_id;
            foreach ($request->file('linkpdf') as $uploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
                $uploadedFile->storeAs($folderPath, $filename, 'public');

                File::create([
                    'program_id' => $program->program_id,
                    'nama'       => $uploadedFile->getClientOriginalName(),
                    'file'       => $folderPath . '/' . $filename,
                    'folder'     => $folderPath,
                ]);
            }
        }

        return redirect()
            ->route('program.edit', $program->program_id)
            ->with('success', 'Program updated successfully! You can continue editing below.');
    }

    public function confirmDelete($id)
    {
        $program = Program::with(['dosen', 'pertemuan'])->findOrFail($id);
        $files = \App\Models\File::where('program_id', $id)->get();

        return view('lecturer.program-delete', compact('program', 'files'));
    }

    public function destroy($id)
    {
        $program = Program::with('files')->findOrFail($id);

        DB::beginTransaction();

        try {
            $programFolder = public_path("storage/program_files/{$program->program_id}");
            $timestamp = now()->format('Ymd_His');
            $backupFolder = public_path("storage/backups/program_{$program->program_id}_{$timestamp}");

            // Ensure backup directory exists
            if (!FileFacade::exists(public_path('storage/backups'))) {
                FileFacade::makeDirectory(public_path('storage/backups'), 0755, true);
            }

            // Move program folder if exists, otherwise create an empty backup folder
            if (FileFacade::exists($programFolder)) {
                FileFacade::moveDirectory($programFolder, $backupFolder);
            } else {
                FileFacade::makeDirectory($backupFolder, 0755, true);
            }

            ProgramBackup::create([
                'program_id'  => $program->program_id,
                'backup_code' => 'BK-' . strtoupper(uniqid()),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            if ($program->files && $program->files->isNotEmpty()) {
                foreach ($program->files as $file) {
                    $oldPath = $file->file;
                    $newPath = str_replace(
                        "program_files/{$program->program_id}",
                        "backups/program_{$program->program_id}_{$timestamp}",
                        $oldPath
                    );
                    $file->update(['file' => $newPath]);
                }
            }

            $program->delete();

            DB::commit();

            return redirect('/program')->with('success', 'Program berhasil dibackup dan dipindahkan ke folder backup.');
        } 
        catch (\Exception $e) {
            DB::rollBack();
            Log::error("Program backup failed: " . $e->getMessage());
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function restoreProgram()
    {
        $deletedPrograms = Program::onlyTrashed()->paginate(5);

        return view('lecturer.program-restore', compact('deletedPrograms'));
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $program = Program::onlyTrashed()->with('files')->findOrFail($id);

            // 🧾 Find the most recent backup related to this program
            $backup = ProgramBackup::where('program_id', $program->program_id)
                                ->latest('created_at')
                                ->first();

            if (!$backup) {
                return back()->with('error', 'Backup data tidak ditemukan untuk program ini.');
            }

            // 🗂 Define paths
            $backupFolderPattern = public_path("storage/backups/program_{$program->program_id}_");
            $backupFolder = collect(glob($backupFolderPattern . '*'))->sortDesc()->first();
            $originalFolder = public_path("storage/program_files/{$program->program_id}");

            // ⚙️ If the program had files, handle the folder + path restoration
            if ($program->files && $program->files->isNotEmpty()) {

                // 🧭 Ensure the backup folder exists
                if (!$backupFolder || !FileFacade::exists($backupFolder)) {
                    return back()->with('error', 'Folder backup tidak ditemukan untuk file program.');
                }

                // 🧱 Ensure destination parent folder exists
                if (!FileFacade::exists(dirname($originalFolder))) {
                    FileFacade::makeDirectory(dirname($originalFolder), 0755, true);
                }

                // 🔄 Move the backup folder back
                FileFacade::moveDirectory($backupFolder, $originalFolder);

                // 🧩 Rewrite each file path in the database
                $backupFolderName = basename($backupFolder); 
                // Example: "program_5_20251103_142355"

                foreach ($program->files as $file) {
                    $oldPath = $file->file;

                    // Normalize slashes for safety
                    $normalizedOldPath = str_replace('\\', '/', $oldPath);

                    // Replace "backups/program_X_timestamp" with "program_files/X"
                    $newPath = str_replace(
                        "backups/{$backupFolderName}",
                        "program_files/{$program->program_id}",
                        $normalizedOldPath
                    );

                    // Update only if changed
                    if ($newPath !== $oldPath) {
                        $file->update(['file' => $newPath]);
                    }
                }
            }

            // ♻️ Restore program (undo soft delete)
            $program->restore();

            // 🧹 Remove the backup record
            $backup->delete();

            DB::commit();

            return redirect()->route('program.restoreProgram')->with('success', 'Program berhasil direstore.');
        } 
        catch (\Exception $e) {
            DB::rollBack();
            Log::error("Restore failed: " . $e->getMessage());
            return back()->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }
}
