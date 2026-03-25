<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\User;
use App\Notifications\DokumenNotification;
use App\Notifications\SubmissionStatusNotification;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function index()
    {
        $dokumens = Dokumen::latest()->paginate(10);

        return view('admin.dokumens.index', compact('dokumens'));
    }

    public function create()
    {
        return view('admin.dokumens.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_dokumen' => ['required', 'in:pdf,docx'],
            'tanggal_deadline' => ['required', 'date', 'after:now'],
        ]);

        $dokumen = Dokumen::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe_dokumen' => $request->tipe_dokumen,
            'tanggal_deadline' => $request->tanggal_deadline,
        ]);

        $dosens = User::where('role', 'dosen')->get();
        foreach ($dosens as $dosen) {
            $dosen->notify(new DokumenNotification($dokumen, 'created'));
        }

        return redirect()->route('admin.dokumens.index')->with('success', 'Dokumen berhasil dibuat.');
    }

    public function edit(Dokumen $dokumen)
    {
        return view('admin.dokumens.edit', compact('dokumen'));
    }

    public function update(Request $request, Dokumen $dokumen)
    {
        $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_dokumen' => ['required', 'in:pdf,docx'],
            'tanggal_deadline' => ['required', 'date'],
        ]);

        $dokumen->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe_dokumen' => $request->tipe_dokumen,
            'tanggal_deadline' => $request->tanggal_deadline,
        ]);

        $dosens = User::where('role', 'dosen')->get();
        foreach ($dosens as $dosen) {
            $dosen->notify(new DokumenNotification($dokumen, 'updated'));
        }

        return redirect()->route('admin.dokumens.index')->with('success', 'Dokumen berhasil diupdate.');
    }

    public function destroy(Dokumen $dokumen)
    {
        $dosens = User::where('role', 'dosen')->get();
        foreach ($dosens as $dosen) {
            $dosen->notify(new DokumenNotification($dokumen, 'deleted'));
        }

        $dokumen->delete();

        return redirect()->route('admin.dokumens.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function submissions(Dokumen $dokumen)
    {
        $dokumens = Dokumen::with(['submissions.user'])->get()->find($dokumen);
        $submissions = $dokumen->submissions()->with('user')->get();
        $totalDosens = User::where('role', 'dosen')->count();

        return view('admin.dokumens.submissions', compact('dokumen', 'submissions', 'totalDosens'));
    }

    public function accept(Request $request, Dokumen $dokumen, $submissionId)
    {
        $submission = $dokumen->submissions()->findOrFail($submissionId);
        $submission->update([
            'status' => 'accepted',
            'catatan' => $request->catatan,
        ]);

        $submission->user->notify(new SubmissionStatusNotification($dokumen, 'accepted', $request->catatan));

        return redirect()->back()->with('success', 'Submission diterima.');
    }

    public function reject(Request $request, Dokumen $dokumen, $submissionId)
    {
        $request->validate([
            'catatan' => ['required', 'string'],
        ]);

        $submission = $dokumen->submissions()->findOrFail($submissionId);
        $submission->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
        ]);

        $submission->user->notify(new SubmissionStatusNotification($dokumen, 'rejected', $request->catatan));

        return redirect()->back()->with('success', 'Submission ditolak.');
    }
}
