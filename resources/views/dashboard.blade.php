<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if(auth()->user()->isAdmin())
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dosen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalDosens'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Dokumen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalDokumens'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pendingSubmissions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['acceptedSubmissions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen Terbaru</h6>
                </div>
                <div class="card-body">
                    @forelse($recentSubmissions as $submission)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-{{ $submission->status === 'accepted' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                    <i class="fas fa-{{ $submission->status === 'accepted' ? 'check' : ($submission->status === 'rejected' ? 'times' : 'clock') }} text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $submission->created_at->format('d/m/Y H:i') }}</div>
                                <strong>{{ $submission->user->name }}</strong> pengumpulan {{ $submission->dokumen->judul }}
                                <br>
                                <span class="badge badge-{{ $submission->status === 'accepted' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Belum ada pengumpulan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen Aktif</h6>
                </div>
                <div class="card-body">
                    @forelse($activeDokumens as $dokumen)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Deadline: {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}</div>
                                <strong>{{ $dokumen->judul }}</strong>
                                <br>
                                <span class="text-muted">{{ $dokumen->submissions->count() }} / {{ $stats['totalDosens'] }} pengumpulan</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Tidak ada dokumen aktif.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @else

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Dokumen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalDokumens'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total upload</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['mySubmissions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pendingSubmissions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Diterima</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['acceptedSubmissions'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengumpulan Saya</h6>
                </div>
                <div class="card-body">
                    @forelse($mySubmissions as $submission)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-{{ $submission->status === 'accepted' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                    <i class="fas fa-{{ $submission->status === 'accepted' ? 'check' : ($submission->status === 'rejected' ? 'times' : 'clock') }} text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">{{ $submission->created_at->format('d/m/Y H:i') }}</div>
                                <strong>{{ $submission->dokumen->judul }}</strong>
                                <br>
                                <span class="badge badge-{{ $submission->status === 'accepted' ? 'success' : ($submission->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($submission->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Belum ada pengumpulan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Dokumen yang Belum Disubmit</h6>
                </div>
                <div class="card-body">
                    @forelse($activeDokumens as $dokumen)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="small text-gray-500">Deadline: {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}</div>
                                <strong>{{ $dokumen->judul }}</strong>
                            </div>
                            <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn btn-sm btn-primary">
                                Submit
                            </a>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Semua dokumen sudah disubmit!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @endif
</x-app-layout>
