<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-file-alt mr-2"></i>{{ __('Dokumen') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Dokumen</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Tipe</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dokumens as $dokumen)
                        @php
                            $submission = $userSubmissions->get($dokumen->id);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dokumen->judul }}</td>
                            <td>{{ $dokumen->deskripsi ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $dokumen->tipe_dokumen == 'pdf' ? 'danger' : 'info' }}">
                                    {{ strtoupper($dokumen->tipe_dokumen) }}
                                </span>
                            </td>
                            <td>
                                {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                                @if($dokumen->isDeadlinePassed())
                                    <span class="badge badge-secondary">Expired</span>
                                @else
                                    <span class="badge badge-success">Aktif</span>
                                @endif
                            </td>
                            <td>
                                @if($submission)
                                    @if($submission->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($submission->isAccepted())
                                        <span class="badge badge-success">Diterima</span>
                                    @elseif($submission->isRejected())
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                    @if($submission->catatan)
                                        <br><small class="text-muted">{{ $submission->catatan }}</small>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum Submit</span>
                                @endif
                            </td>
                            <td>
                                @if(!$dokumen->isDeadlinePassed())
                                    <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn btn-{{ $submission ? 'info' : 'primary' }} btn-sm">
                                        <i class="fas fa-upload mr-1"></i> {{ $submission ? 'Update' : 'Submit' }}
                                    </a>
                                @elseif($submission)
                                    <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">Deadline passed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada dokumen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
