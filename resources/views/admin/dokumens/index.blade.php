<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-file-alt mr-2"></i>{{ __('Kelola Dokumen') }}
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
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Dokumen</h6>
            <a href="{{ route('admin.dokumens.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Dokumen
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Tipe</th>
                            <th>Deadline</th>
                            <th>Pengumpulan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dokumens as $dokumen)
                        <tr>
                            <td>{{ $loop->iteration + ($dokumens->currentPage() - 1) * $dokumens->perPage() }}</td>
                            <td>{{ $dokumen->judul }}</td>
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
                                <a href="{{ route('admin.dokumens.submissions', $dokumen) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i> {{ $dokumen->submission_count }} / {{ \App\Models\User::where('role', 'dosen')->count() }} pengumpulan
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.dokumens.edit', $dokumen) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.dokumens.destroy', $dokumen) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $dokumens->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
