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

        return view('dokumens.submit', compact('dokumen', 'existingSubmission'));
    }

    public function storeSubmit(Request $request, Dokumen $dokumen)
    {
        $user = auth()->user();

        $existingSubmission = DokumenSubmission::where('dokumen_id', $dokumen->id)
            ->where('user_id', $user->id)
            ->first();

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:'.($dokumen->tipe_dokumen == 'pdf' ? 'pdf' : 'docx,doc'),
                'max:5120',
            ],
        ]);

        $file = $request->file('file');
        $filename = $dokumen->id.'_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('dokumens', $filename, 'public');

        if ($existingSubmission) {
            if ($existingSubmission->file_path && Storage::disk('public')->exists($existingSubmission->file_path)) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
            $existingSubmission->update([
                'file_path' => $path,
                'tanggal_submit' => now(),
                'status' => 'pending',
                'catatan' => null,
            ]);
            $message = 'Dokumen berhasil diupdate.';
        } else {
            DokumenSubmission::create([
                'dokumen_id' => $dokumen->id,
                'user_id' => $user->id,
                'file_path' => $path,
                'tanggal_submit' => now(),
                'status' => 'pending',
            ]);
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
            ->with('dokumen')
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
        $message .= "\n _Dikirim dari Sistem Pemberitahuan Dosen_";

        $result = $whatsAppService->sendMessage($user->no_telepon, $message);

        if ($result) {
            return redirect()->back()->with('success', 'Notifikasi WhatsApp berhasil dikirim.');
        }

        return redirect()->back()->with('error', 'Gagal mengirim notifikasi WhatsApp.');
    }
}
