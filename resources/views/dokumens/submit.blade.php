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
            <table class="mb-6 text-sm">
                <tbody>
                    <tr>
                        <td class="pb-1 pr-4 text-gray-600">Judul</td>
                        <td class="pb-1 pr-2 text-gray-600">:</td>
                        <td class="pb-1 font-medium text-gray-900">{{ $dokumen->judul }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4 text-gray-600">Deskripsi</td>
                        <td class="pb-1 pr-2 text-gray-600">:</td>
                        <td class="pb-1 text-gray-900">{{ $dokumen->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4 text-gray-600">Tipe File</td>
                        <td class="pb-1 pr-2 text-gray-600">:</td>
                        <td class="pb-1 text-gray-900">{{ strtoupper($dokumen->tipe_dokumen) }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4 text-gray-600">Deadline</td>
                        <td class="pb-1 pr-2 text-gray-600">:</td>
                        <td class="pb-1 text-gray-900">
                            <div class="flex items-center gap-2">
                                {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                                <span class="badge {{ $dokumen->isDeadlinePassed() ? 'badge-secondary' : 'badge-success' }}">
                                    {{ $dokumen->isDeadlinePassed() ? 'Expired' : 'Aktif' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            @if($existingSubmission)
            <div class="mb-6 rounded-lg border {{ $existingSubmission->isRejected() ? 'border-red-200 bg-red-50' : 'bg-gray-50 border-gray-200' }} p-4">
                <p class="text-sm font-medium text-gray-700 mb-2">File yang sudah diupload ({{ $existingSubmission->files->count() }} file):</p>
                @forelse($existingSubmission->files as $file)
                <div class="flex items-center gap-10 mb-2">
                    <i class="fas fa-file-alt text-primary-500"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 truncate">{{ $file->original_name }}</p>
                    </div>
                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn-info btn-sm shrink-0">
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                </div>
                @empty
                <p class="text-sm text-gray-400">Belum ada file.</p>
                @endforelse
                <p class="text-xs text-gray-400 mt-2">Submitted: {{ $existingSubmission->tanggal_submit->format('d/m/Y H:i') }}</p>
                @if($existingSubmission->catatan)
                    <div class="mt-3 rounded border border-gray-200 bg-white p-3 text-sm">
                        <strong class="text-gray-600">Catatan Admin:</strong> {{ $existingSubmission->catatan }}
                    </div>
                @endif
            </div>

            @if($existingSubmission->isAccepted())
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                <i class="fas fa-check-circle mr-1"></i> Pengumpulan Anda sudah diterima.
            </div>
            @elseif($existingSubmission->isRejected())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <i class="fas fa-times-circle mr-1"></i> Pengumpulan Anda ditolak. Upload file baru untuk mengganti.
            </div>
            @else
            <div class="mb-6 rounded-lg bg-cyan-50 border border-cyan-200 px-4 py-3 text-sm text-cyan-700">
                <i class="fas fa-info-circle mr-1"></i> Upload file baru untuk mengganti file sebelumnya, atau biarkan kosong jika tidak ingin mengubah.
            </div>
            @endif
            @endif

            @if(!$existingSubmission || !$existingSubmission->isAccepted())
            <form method="POST" action="{{ route('dokumens.store-submit', $dokumen) }}" enctype="multipart/form-data" id="submitForm">
                @csrf

                <div class="mb-6">
                    <x-input-label for="files" value="Pilih File ({{ strtoupper($dokumen->tipe_dokumen) }})" />
                    <div class="mt-1">
                        <div class="relative flex items-center justify-center w-full border-2 border-dashed rounded-lg cursor-pointer border-gray-300 hover:border-primary-400 bg-gray-50 hover:bg-primary-50/30 transition-colors py-6 px-4"
                             id="dropZone">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600">Klik untuk pilih file atau drag & drop</p>
                                <p class="text-xs text-gray-400 mt-1">Format: .{{ $dokumen->tipe_dokumen }} | Maks. 5MB per file</p>
                                @if(!$existingSubmission)
                                <p class="text-xs text-gray-400">Bisa upload beberapa file sekaligus</p>
                                @else
                                <p class="text-xs text-gray-400">Upload file baru akan mengganti semua file sebelumnya</p>
                                @endif
                            </div>
                            <input type="file" id="files" name="files[]"
                                   accept=".{{ $dokumen->tipe_dokumen }}"
                                   multiple
                                   @if(!$existingSubmission) required @endif
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer
                                          @error('files') [&]:border-red-300 @enderror">
                        </div>
                        @error('files')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('files.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="fileList" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</p>
                        <div id="selectedFiles" class="space-y-2"></div>
                    </div>
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
            @else
            <div class="flex items-center gap-3">
                <a href="{{ route('dokumens.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        const fileInput = document.getElementById('files');
        const dropZone = document.getElementById('dropZone');
        const fileList = document.getElementById('fileList');
        const selectedFiles = document.getElementById('selectedFiles');

        function updateFileList() {
            selectedFiles.innerHTML = '';
            const files = fileInput.files;

            if (files.length === 0) {
                fileList.classList.add('hidden');
                return;
            }

            fileList.classList.remove('hidden');

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 bg-gray-50 rounded px-3 py-2';
                div.innerHTML = `
                    <i class="fas fa-file-alt text-primary-500"></i>
                    <span class="flex-1 text-sm text-gray-800 truncate">${file.name}</span>
                    <span class="text-xs text-gray-400">${sizeMB} MB</span>
                    <button type="button" class="text-red-400 hover:text-red-600 text-sm" data-index="${i}" title="Hapus">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                selectedFiles.appendChild(div);
            }
        }

        if (fileInput) {
            fileInput.addEventListener('change', updateFileList);
        }

        if (dropZone) {
            ['dragenter', 'dragover'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-primary-500', 'bg-primary-50');
                });
            });
            ['dragleave', 'drop'].forEach(evt => {
                dropZone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-primary-500', 'bg-primary-50');
                });
            });
            dropZone.addEventListener('drop', (e) => {
                fileInput.files = e.dataTransfer.files;
                updateFileList();
            });
        }
    </script>
    @endpush
</x-app-layout>
