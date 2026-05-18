<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Dashboard') }}
    </x-slot>

    @if(auth()->user()->isAdmin())
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        {{-- Total Dosen --}}
        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-primary-600">Total Dosen</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['totalDosens'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-users text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Total Dokumen --}}
        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Dokumen</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['totalDokumens'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-file-alt text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pendingSubmissions'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-clock text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        {{-- Aktif --}}
        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-cyan-600">Aktif</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['acceptedSubmissions'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-check-circle text-lg text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Dokumen Terbaru --}}
        <div class="card">
            <div class="card-header">Dokumen Terbaru</div>
            <div class="card-body">
                @forelse($recentSubmissions as $submission)
                    @php
                        $statusColors = [
                            'accepted' => 'bg-emerald-500',
                            'rejected' => 'bg-red-500',
                            'pending' => 'bg-amber-500',
                        ];
                        $statusIcons = [
                            'accepted' => 'fa-check',
                            'rejected' => 'fa-times',
                            'pending' => 'fa-clock',
                        ];
                        $badgeClass = [
                            'accepted' => 'badge-success',
                            'rejected' => 'badge-danger',
                            'pending' => 'badge-warning',
                        ];
                    @endphp
                    <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                        <div class="icon-circle {{ $statusColors[$submission->status] ?? 'bg-gray-400' }} shrink-0">
                            <i class="fas {{ $statusIcons[$submission->status] ?? 'fa-bell' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400">{{ $submission->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $submission->user->name }} - {{ $submission->dokumen->judul }}</p>
                            <span class="badge mt-1 {{ $badgeClass[$submission->status] ?? 'badge-secondary' }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 py-6">Belum ada pengumpulan.</p>
                @endforelse
            </div>
        </div>

        {{-- Dokumen Aktif --}}
        <div class="card">
            <div class="card-header">Dokumen Aktif</div>
            <div class="card-body">
                @forelse($activeDokumens as $dokumen)
                    <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                        <div class="icon-circle bg-primary-500 shrink-0">
                            <i class="fas fa-file-alt text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400">Deadline: {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $dokumen->judul }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $dokumen->submissions->count() }} / {{ $stats['totalDosens'] }} pengumpulan</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 py-6">Tidak ada dokumen aktif.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Rekap Dokumen & Submission --}}
    <div class="mt-6 card">
        <div class="card-header">Rekap Dokumen dan Submission</div>
        <div class="card-body !p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Judul Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($rekapDokumens as $dokumen)
                            @php
                                $percentage = $stats['totalDosens'] > 0 ? round(($dokumen->submissions_count / $stats['totalDosens']) * 100) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $dokumen->judul }}</td>
                                <td class="px-6 py-4">
                                    <span class="badge {{ $dokumen->tipe_dokumen == 'pdf' ? 'badge-danger' : 'badge-info' }}">
                                        {{ strtoupper($dokumen->tipe_dokumen) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-700">{{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}</div>
                                    <span class="badge mt-1 {{ $dokumen->isDeadlinePassed() ? 'badge-secondary' : 'badge-success' }}">
                                        {{ $dokumen->isDeadlinePassed() ? 'Expired' : 'Aktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 min-w-[200px]">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                                            <div class="bg-primary-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-600 font-medium whitespace-nowrap">{{ $dokumen->submissions_count }} / {{ $stats['totalDosens'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($percentage == 100)
                                        <span class="badge badge-success">Lengkap</span>
                                    @elseif($dokumen->isDeadlinePassed())
                                        <span class="badge badge-danger">Ditutup</span>
                                    @else
                                        <span class="badge badge-warning">Proses</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.dokumens.submissions', $dokumen) }}" class="btn-primary btn-sm">
                                        Lihat Detail <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">
                                    Belum ada dokumen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-primary-600">Total Dokumen</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['totalDokumens'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-file-alt text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Upload</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['mySubmissions'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-check text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pendingSubmissions'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-clock text-lg text-gray-400"></i>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-cyan-600">Diterima</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['acceptedSubmissions'] }}</p>
                </div>
                <div class="icon-circle bg-gray-100">
                    <i class="fas fa-check-circle text-lg text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="card">
            <div class="card-header">Pengumpulan Saya</div>
            <div class="card-body">
                @forelse($mySubmissions as $submission)
                    @php
                        $stColors = ['accepted' => 'bg-emerald-500', 'rejected' => 'bg-red-500', 'pending' => 'bg-amber-500'];
                        $stIcons = ['accepted' => 'fa-check', 'rejected' => 'fa-times', 'pending' => 'fa-clock'];
                        $stBadge = ['accepted' => 'badge-success', 'rejected' => 'badge-danger', 'pending' => 'badge-warning'];
                    @endphp
                    <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                        <div class="icon-circle {{ $stColors[$submission->status] ?? 'bg-gray-400' }} shrink-0">
                            <i class="fas {{ $stIcons[$submission->status] ?? 'fa-bell' }} text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400">{{ $submission->created_at->format('d/m/Y H:i') }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $submission->dokumen->judul }}</p>
                            <span class="badge mt-1 {{ $stBadge[$submission->status] ?? 'badge-secondary' }}">
                                {{ ucfirst($submission->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 py-6">Belum ada pengumpulan.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">Dokumen yang Belum Disubmit</div>
            <div class="card-body">
                @forelse($activeDokumens as $dokumen)
                    <div class="flex items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-b border-gray-100' : '' }}">
                        <div class="icon-circle bg-amber-500 shrink-0">
                            <i class="fas fa-file-alt text-white text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400">Deadline: {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $dokumen->judul }}</p>
                        </div>
                        <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn-primary btn-sm shrink-0">
                            Submit
                        </a>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 py-6">Semua dokumen sudah disubmit!</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
