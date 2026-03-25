<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-upload mr-2"></i>{{ __('Submit Dokumen') }}
        </h2>
    </x-slot>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Submit Dokumen</h6>
            @if($existingSubmission)
                @if($existingSubmission->isPending())
                    <span class="badge badge-warning">Pending</span>
                @elseif($existingSubmission->isAccepted())
                    <span class="badge badge-success">Diterima</span>
                @elseif($existingSubmission->isRejected())
                    <span class="badge badge-danger">Ditolak</span>
                @endif
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4">
                <strong>Judul:</strong> {{ $dokumen->judul }}<br>
                <strong>Deskripsi:</strong> {{ $dokumen->deskripsi ?? '-' }}<br>
                <strong>Tipe File:</strong> {{ strtoupper($dokumen->tipe_dokumen) }}<br>
                <strong>Deadline:</strong> {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                @if($dokumen->isDeadlinePassed())
                    <span class="badge badge-secondary">Expired</span>
                @else
                    <span class="badge badge-success">Aktif</span>
                @endif
            </div>

            @if($existingSubmission)
            <div class="card bg-light mb-4 {{ $existingSubmission->isRejected() ? 'border border-danger' : '' }}">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf text-danger fa-2x mr-3"></i>
                        <div class="flex-grow-1">
                            <strong>{{ basename($existingSubmission->file_path) }}</strong>
                            <br>
                            <small class="text-muted">Submitted: {{ $existingSubmission->tanggal_submit->format('d/m/Y H:i') }}</small>
                        </div>
                        <a href="{{ Storage::url($existingSubmission->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                    @if($existingSubmission->catatan)
                        <div class="mt-3 p-2 bg-white rounded border">
                            <small class="text-muted"><strong>Catatan Admin:</strong> {{ $existingSubmission->catatan }}</small>
                        </div>
                    @endif
                </div>
            </div>

            @if($existingSubmission->isRejected())
            <div class="alert alert-danger mb-4">
                <i class="fas fa-times-circle mr-1"></i>
                Pengumpulan Anda ditolak. Silakan upload file baru.
            </div>
            @elseif($existingSubmission->isAccepted())
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle mr-1"></i>
                Pengumpulan Anda sudah diterima.
            </div>
            @else
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle mr-1"></i>
                Upload file baru untuk mengganti file sebelumnya.
            </div>
            @endif
            @endif

            <form method="POST" action="{{ route('dokumens.store-submit', $dokumen) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="file">Pilih File ({{ strtoupper($dokumen->tipe_dokumen) }})</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="file" name="file" accept=".{{ $dokumen->tipe_dokumen }}" {{ $existingSubmission && $existingSubmission->isAccepted() ? '' : 'required' }}>
                        <label class="custom-file-label" for="file" id="fileLabel">Pilih file...</label>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="form-text text-muted">Maksimum ukuran file: 5MB</small>
                </div>

                <hr>

                <a href="{{ route('dokumens.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload mr-1"></i> {{ $existingSubmission ? 'Update' : 'Submit' }}
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file...';
            document.getElementById('fileLabel').textContent = fileName;
        });
    </script>
    @endpush
</x-app-layout>
