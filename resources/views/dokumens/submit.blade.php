<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-upload mr-2"></i>{{ __('Submit Dokumen') }}
    </x-slot>

    <div class="card">
        <div class="card-header flex flex-wrap items-center justify-between gap-3">
            <span>Submit Dokumen</span>
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
            <div class="mb-6 space-y-1 text-sm">
                <p><strong>Judul:</strong> {{ $dokumen->judul }}</p>
                <p><strong>Deskripsi:</strong> {{ $dokumen->deskripsi ?? '-' }}</p>
                <p><strong>Tipe File:</strong> {{ strtoupper($dokumen->tipe_dokumen) }}</p>
                <p>
                    <strong>Deadline:</strong> {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                    <span class="badge ml-2 {{ $dokumen->isDeadlinePassed() ? 'badge-secondary' : 'badge-success' }}">
                        {{ $dokumen->isDeadlinePassed() ? 'Expired' : 'Aktif' }}
                    </span>
                </p>
            </div>

            @if($existingSubmission)
            <div class="mb-6 rounded-lg border {{ $existingSubmission->isRejected() ? 'border-red-200 bg-red-50' : 'bg-gray-50 border-gray-200' }} p-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 truncate">{{ basename($existingSubmission->file_path) }}</p>
                        <p class="text-xs text-gray-400">Submitted: {{ $existingSubmission->tanggal_submit->format('d/m/Y H:i') }}</p>
                    </div>
                    <a href="{{ Storage::url($existingSubmission->file_path) }}" target="_blank" class="btn-info btn-sm shrink-0">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                </div>
                @if($existingSubmission->catatan)
                    <div class="mt-3 rounded border border-gray-200 bg-white p-3 text-sm">
                        <strong class="text-gray-600">Catatan Admin:</strong> {{ $existingSubmission->catatan }}
                    </div>
                @endif
            </div>

            @if($existingSubmission->isRejected())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <i class="fas fa-times-circle mr-1"></i> Pengumpulan Anda ditolak. Silakan upload file baru.
            </div>
            @elseif($existingSubmission->isAccepted())
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                <i class="fas fa-check-circle mr-1"></i> Pengumpulan Anda sudah diterima.
            </div>
            @else
            <div class="mb-6 rounded-lg bg-cyan-50 border border-cyan-200 px-4 py-3 text-sm text-cyan-700">
                <i class="fas fa-info-circle mr-1"></i> Upload file baru untuk mengganti file sebelumnya.
            </div>
            @endif
            @endif

            <form method="POST" action="{{ route('dokumens.store-submit', $dokumen) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <x-input-label for="file" value="Pilih File ({{ strtoupper($dokumen->tipe_dokumen) }})" />
                    <div class="mt-1">
                        <input type="file" id="file" name="file" accept=".{{ $dokumen->tipe_dokumen }}"
                               {{ $existingSubmission && $existingSubmission->isAccepted() ? '' : 'required' }}
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100
                                      @error('file') border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <p class="mt-1 text-xs text-gray-400">Maksimum ukuran file: 5MB</p>
                </div>

                <hr class="my-6 border-gray-200">

                <div class="flex items-center gap-3">
                    <a href="{{ route('dokumens.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload mr-1"></i> {{ $existingSubmission ? 'Update' : 'Submit' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file...';
            // Update any label showing filename if needed
        });
    </script>
    @endpush
</x-app-layout>
