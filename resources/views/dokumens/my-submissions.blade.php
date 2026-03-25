<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-list mr-2"></i>{{ __('Pengumpulan Saya') }}
        </h2>
    </x-slot>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Dokumen yang Sudah Disubmit</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Dokumen</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Tanggal Submit</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $submission->dokumen->judul }}</td>
                            <td>
                                <span class="badge badge-{{ $submission->dokumen->tipe_dokumen == 'pdf' ? 'danger' : 'info' }}">
                                    {{ strtoupper($submission->dokumen->tipe_dokumen) }}
                                </span>
                            </td>
                            <td>
                                @if($submission->isPending())
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($submission->isAccepted())
                                    <span class="badge badge-success">Diterima</span>
                                @elseif($submission->isRejected())
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $submission->catatan ?? '-' }}</td>
                            <td>{{ $submission->tanggal_submit->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                            <td>
                                <form action="{{ route('dokumens.submissions.send-whatsapp', $submission) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fab fa-whatsapp"></i> Kirim WA
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pengumpulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <hr>
            <a href="{{ route('dokumens.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</x-app-layout>
