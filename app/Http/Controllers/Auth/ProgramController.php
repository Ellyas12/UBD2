<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Pertemuan;
use App\Models\Dosen;
use App\Models\File;
use App\Models\FileBackup;
use App\Models\ProgramBackup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FileFacade;

class ProgramController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dosen = $user->dosen ?? null;
        $pertemuanList = Pertemuan::all();
        $programList = Program::all();

        $myPrograms = [];
        if ($dosen) {
            $myPrograms = Program::with(['dosen', 'pertemuan'])
                ->where('dosen_id', $dosen->dosen_id)
                ->latest('tanggal')
                ->get();
        }

        return view('lecturer.program', compact('user', 'dosen', 'pertemuanList', 'programList', 'myPrograms'));
    }

    public function createProgram()
    {
        $pertemuanList = Pertemuan::all();
        return view('lecturer.program-create', compact('pertemuanList'));
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

        // ✅ Validate inputs
        $request->validate([
            'jenis'         => 'required|string|max:255',
            'bidang'        => 'required|string|max:255',
            'topik'         => 'required|string|max:255',
            'judul'         => 'required|string|max:255',
            'ketua'         => 'required|string|max:255',
            'anggota'       => 'nullable|string',
            'tanggal'       => 'required|date',
            'biaya'         => 'required|numeric',
            'sumber_biaya'  => 'required|string|max:255',
            'pertemuan_id'  => 'required|exists:pertemuan,pertemuan_id',
            'deskripsi'     => 'nullable|string',
            'linkweb'       => 'nullable|string',
            'linkpdf'       => 'nullable|array|max:5', // max 5 files
            'linkpdf.*'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:5120', // each ≤5MB
        ]);

        // ✅ Check total file size (≤10MB)
        $totalSize = 0;
        if ($request->hasFile('linkpdf')) {
            foreach ($request->file('linkpdf') as $file) {
                $totalSize += $file->getSize();
            }
        }

        if ($totalSize > 10 * 1024 * 1024) { // 10MB
            return back()->with('error', 'Total ukuran semua file tidak boleh melebihi 10MB.')->withInput();
        }

        // ✅ Create program
        $program = Program::create([
            'jenis'        => $request->jenis,
            'bidang'       => $request->bidang,
            'topik'        => $request->topik,
            'judul'        => $request->judul,
            'ketua'        => $request->ketua,
            'anggota'      => $request->anggota,
            'tanggal'      => $request->tanggal,
            'biaya'        => $request->biaya,
            'sumber_biaya' => $request->sumber_biaya,
            'pertemuan_id' => $request->pertemuan_id,
            'linkweb'      => $request->linkweb,
            'deskripsi'    => $request->deskripsi,
            'dosen_id'     => $dosen->dosen_id,
        ]);

        // ✅ Handle multiple file uploads
        if ($request->hasFile('linkpdf')) {
            $folderPath = 'program_files/' . $program->program_id;

            foreach ($request->file('linkpdf') as $uploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

                // Store file in storage/app/public/program_files/{program_id}
                $uploadedFile->storeAs($folderPath, $filename, 'public');

                // Save file record in database
                File::create([
                    'program_id' => $program->program_id,
                    'nama'       => $uploadedFile->getClientOriginalName(),
                    'file'       => $folderPath . '/' . $filename,
                    'folder'     => $folderPath,
                ]);
            }
        }

        return redirect()->route('program')
            ->with('success', 'Program dan file berhasil ditambahkan!');
            dd($request->file('linkpdf'));
    }

    public function view($id)
    {
        $program = Program::with(['dosen', 'pertemuan'])->findOrFail($id);
        $files = \App\Models\File::where('program_id', $id)->get();

        return view('lecturer.program-view', compact('program', 'files'));
    }

    public function edit($id)
    {
        $program = Program::with('files')->findOrFail($id);
        $pertemuanList = Pertemuan::all();

        return view('lecturer.program-edit', compact('program', 'pertemuanList'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'jenis'         => 'required|string|max:255',
            'bidang'        => 'required|string|max:255',
            'topik'         => 'required|string|max:255',
            'judul'         => 'required|string|max:255',
            'ketua'         => 'required|string|max:255',
            'anggota'       => 'nullable|string',
            'tanggal'       => 'required|date',
            'biaya'         => 'required|numeric',
            'sumber_biaya'  => 'required|string|max:255',
            'pertemuan_id'  => 'required|exists:pertemuan,pertemuan_id',
            'linkweb'       => 'nullable|string',
            'deskripsi'     => 'nullable|string',
            'linkpdf.*'     => 'nullable|file|mimes:pdf,doc,docx,zip,jpg,jpeg,png|max:5120',
        ]);

        // ✅ Update program info
        $program->update($request->only([
            'jenis', 'bidang', 'topik', 'judul', 'ketua', 'anggota', 
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

    public function deleteFile($file_id)
    {
        $file = File::findOrFail($file_id);

        // ✅ Backup file record to file_backup table
        FileBackup::create([
            'program_id' => $file->program_id,
            'nama'       => $file->nama,
            'file'       => $file->file,
            'folder'     => $file->folder,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => now(),
        ]);

        // ✅ Copy actual file to backup folder (in public/storage/backups/)
        $originalFile = 'public/' . $file->file;
        $backupFile = 'public/backups/files_' . $file->file_id . '_' . now()->format('YmdHis') . '/' . basename($file->file);

        if (Storage::exists($originalFile)) {
            Storage::copy($originalFile, $backupFile);
            Storage::delete($originalFile);
        }

        // ✅ Delete record from main file table
        $file->delete();

        return back()->with('success', 'File telah dipindahkan ke backup dan dihapus dari daftar utama!');
    }

    public function confirmDelete($id)
    {
        $program = Program::findOrFail($id);
        return view('lecturer.program-delete', compact('program'));
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);

        DB::beginTransaction();

        try {
            // 🗂 Define real folder paths (directly under public/storage)
            $programFolder = public_path("storage/program_files/{$program->program_id}");
            $backupFolder  = public_path("storage/backups/folder_{$program->program_id}_" . now()->format('YmdHis'));

            // 🧭 Create the backups folder if needed
            FileFacade::ensureDirectoryExists(dirname($backupFolder));

            // 🪶 Move the physical folder
            if (file_exists($programFolder)) {
                FileFacade::move($programFolder, $backupFolder);
            }

            // 🧾 Create Program backup entry
            $programBackup = ProgramBackup::create($program->toArray());

            // 📂 Backup each related file
            foreach ($program->files as $file) {
                // Get old path (relative from public/storage)
                $oldFilePath = $file->file;

                // Build new relative path (replacing "program_files/" with "backups/")
                $newFilePath = str_replace('program_files/', 'backups/', $oldFilePath);

                FileBackup::create([
                    'program_id' => $file->program_id,
                    'nama'       => $file->nama,
                    'file'       => $newFilePath, // ✅ new path now points to backups
                    'folder'     => 'backups',    // optional reference
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 🧹 Clean up database
            $program->files()->delete();
            $program->delete();

            DB::commit();

            return redirect()
                ->route('program')
                ->with('success', 'Program and files successfully backed up to /public/storage/backups/.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}
