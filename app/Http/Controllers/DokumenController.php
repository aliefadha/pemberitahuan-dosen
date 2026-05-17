<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\DokumenSubmission;
use App\Models\User;
use App\Notifications\NewSubmissionNotification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function index()
    {
        $dokumens = Dokumen::latest()->get();

        $userSubmissions = auth()->user()->dokumenSubmissions()->get()->keyBy('dokumen_id');

        return view('dokumens.index', compact('dokumens', 'userSubmissions'));
    }

    public function submit(Dokumen $dokumen)
    {
        $user = auth()->user();
        $existingSubmission = DokumenSubmission::where('dokumen_id', $dokumen->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingSubmission) {
            $existingSubmission->load('files');
        }

        return view('dokumens.submit', compact('dokumen', 'existingSubmission'));
    }

    public function storeSubmit(Request $request, Dokumen $dokumen)
    {
        $user = auth()->user();

        $existingSubmission = DokumenSubmission::where('dokumen_id', $dokumen->id)
            ->where('user_id', $user->id)
            ->first();

        $allowedMimes = $dokumen->tipe_dokumen == 'pdf' ? 'pdf' : 'docx,doc';

        $rules = [
            'files.*' => [
                'file',
                'mimes:'.$allowedMimes,
                'max:5120',
            ],
        ];

        if ($existingSubmission) {
            $rules['files'] = ['nullable', 'array'];
        } else {
            $rules['files'] = ['required', 'array', 'min:1'];
        }

        $request->validate($rules);

        $uploadedFiles = $request->hasFile('files') ? $request->file('files') : [];

        if ($existingSubmission) {
            if (! empty($uploadedFiles)) {
                foreach ($existingSubmission->files as $existingFile) {
                    if (Storage::disk('public')->exists($existingFile->file_path)) {
                        Storage::disk('public')->delete($existingFile->file_path);
                    }
                }
                $existingSubmission->files()->delete();

                foreach ($uploadedFiles as $file) {
                    $filename = $dokumen->id.'_'.$user->id.'_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumens', $filename, 'public');
                    $existingSubmission->files()->create([
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }

                $existingSubmission->update([
                    'tanggal_submit' => now(),
                    'status' => 'pending',
                    'catatan' => null,
                ]);
            }

            $message = 'Dokumen berhasil diupdate.';
        } else {
            $submission = DokumenSubmission::create([
                'dokumen_id' => $dokumen->id,
                'user_id' => $user->id,
                'tanggal_submit' => now(),
                'status' => 'pending',
            ]);

            foreach ($uploadedFiles as $file) {
                $filename = $dokumen->id.'_'.$user->id.'_'.time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $path = $file->storeAs('dokumens', $filename, 'public');
                $submission->files()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }

            $message = 'Dokumen berhasil disubmit.';

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewSubmissionNotification($dokumen, $user));
            }
        }

        return redirect()->route('dokumens.index')->with('success', $message);
    }

    public function submissions()
    {
        $submissions = auth()->user()->dokumenSubmissions()
            ->with(['dokumen', 'files'])
            ->latest()
            ->get();

        return view('dokumens.my-submissions', compact('submissions'));
    }

    public function sendWhatsApp(DokumenSubmission $submission)
    {
        $user = auth()->user();

        if ($submission->user_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        if (! $user->no_telepon) {
            return redirect()->back()->with('error', 'Nomor WhatsApp belum diisi di profil.');
        }

        $whatsAppService = app(WhatsAppService::class);

        if (! $whatsAppService->isConnected()) {
            return redirect()->back()->with('error', 'WhatsApp belum terhubung.');
        }

        $statusText = match ($submission->status) {
            'pending' => 'Pending',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            default => $submission->status,
        };

        $message = "📋 *Pengingat Upload Dokumen*\n\n";
        $message .= "📌 Judul: {$submission->dokumen->judul}\n";
        $message .= '📝 Tipe: '.strtoupper($submission->dokumen->tipe_dokumen)."\n";
        $message .= "📊 Status: {$statusText}\n";

        if ($submission->catatan) {
            $message .= "💬 Catatan: {$submission->catatan}\n";
        }

        $message .= "📅 Tanggal Pengumpulan: {$submission->tanggal_submit->format('d/m/Y H:i')}\n";

        $submission->load('files');
        if ($submission->files->count() > 0) {
            $message .= "\n📎 *File yang dikumpulkan:*\n";
            foreach ($submission->files as $file) {
                $message .= "• {$file->original_name}\n";
            }
        }

        $message .= "\n _Dikirim dari Sistem Pemberitahuan Dosen_";

        $result = $whatsAppService->sendMessage($user->no_telepon, $message);

        if ($result) {
            return redirect()->back()->with('success', 'Notifikasi WhatsApp berhasil dikirim.');
        }

        return redirect()->back()->with('error', 'Gagal mengirim notifikasi WhatsApp.');
    }
}
