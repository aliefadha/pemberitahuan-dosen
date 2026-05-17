<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-list mr-2"></i>{{ __('Pengumpulan Saya') }}
    </x-slot>

    <div class="card">
        <div class="card-header">Daftar Dokumen yang Sudah Disubmit</div>
        <div class="card-body !p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Judul Dokumen</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tanggal Submit</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">File</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($submissions as $submission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $submission->dokumen->judul }}</td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $submission->dokumen->tipe_dokumen == 'pdf' ? 'badge-danger' : 'badge-info' }}">
                                    {{ strtoupper($submission->dokumen->tipe_dokumen) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($submission->isPending())
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($submission->isAccepted())
                                    <span class="badge badge-success">Diterima</span>
                                @elseif($submission->isRejected())
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $submission->catatan ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $submission->tanggal_submit->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                @foreach($submission->files as $file)
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-800 hover:underline mb-1 mr-2">
                                        <i class="fas fa-file-alt text-xs"></i> {{ \Illuminate\Support\Str::limit($file->original_name, 25) }}
                                    </a>
                                @endforeach
                                @if($submission->files->isEmpty())
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('dokumens.submissions.send-whatsapp', $submission) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-success btn-sm">
                                        <i class="fab fa-whatsapp mr-1"></i> Kirim WA
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">Belum ada pengumpulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                <a href="{{ route('dokumens.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
