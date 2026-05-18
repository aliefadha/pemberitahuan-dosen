<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\DokumenSubmission;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $stats = [
                'totalDosens' => User::where('role', 'dosen')->count(),
                'totalDokumens' => Dokumen::count(),
                'totalSubmissions' => DokumenSubmission::count(),
                'pendingSubmissions' => DokumenSubmission::where('status', 'pending')->count(),
                'acceptedSubmissions' => DokumenSubmission::where('status', 'accepted')->count(),
                'rejectedSubmissions' => DokumenSubmission::where('status', 'rejected')->count(),
            ];

            $recentSubmissions = DokumenSubmission::with(['user', 'dokumen'])
                ->latest()
                ->limit(5)
                ->get();

            $activeDokumens = Dokumen::where('tanggal_deadline', '>', now())->get();
            $rekapDokumens = Dokumen::withCount('submissions')->latest()->get();

            return view('dashboard', compact('stats', 'recentSubmissions', 'activeDokumens', 'rekapDokumens'));
        } else {
            $stats = [
                'totalDokumens' => Dokumen::count(),
                'mySubmissions' => DokumenSubmission::where('user_id', $user->id)->count(),
                'pendingSubmissions' => DokumenSubmission::where('user_id', $user->id)->where('status', 'pending')->count(),
                'acceptedSubmissions' => DokumenSubmission::where('user_id', $user->id)->where('status', 'accepted')->count(),
            ];

            $mySubmissions = DokumenSubmission::where('user_id', $user->id)
                ->with('dokumen')
                ->latest()
                ->limit(5)
                ->get();

            $activeDokumens = Dokumen::where('tanggal_deadline', '>', now())
                ->whereNotIn('id', function ($query) use ($user) {
                    $query->select('dokumen_id')
                        ->from('dokumen_submissions')
                        ->where('user_id', $user->id);
                })
                ->get();

            return view('dashboard', compact('stats', 'mySubmissions', 'activeDokumens'));
        }
    }
}
