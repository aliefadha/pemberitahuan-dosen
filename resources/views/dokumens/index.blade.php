<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-file-alt mr-2"></i>{{ __('Dokumen') }}
    </x-slot>

    <div class="card">
        <div class="card-header">Daftar Dokumen</div>
        <div class="card-body !p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($dokumens as $dokumen)
                        @php $submission = $userSubmissions->get($dokumen->id); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $dokumen->judul }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $dokumen->deskripsi ?? '-' }}</td>
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
                            <td class="px-6 py-4">
                                @if($submission)
                                    @if($submission->isPending())
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($submission->isAccepted())
                                        <span class="badge badge-success">Diterima</span>
                                    @elseif($submission->isRejected())
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                    @if($submission->catatan)
                                        <div class="text-xs text-gray-400 mt-1">{{ $submission->catatan }}</div>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum Submit</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if(!$dokumen->isDeadlinePassed())
                                    <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn-sm {{ $submission ? 'btn-info' : 'btn-primary' }}">
                                        <i class="fas fa-upload mr-1"></i> {{ $submission ? 'Update' : 'Submit' }}
                                    </a>
                                @elseif($submission)
                                    <a href="{{ route('dokumens.submit', $dokumen) }}" class="btn-info btn-sm">
                                        <i class="fas fa-eye mr-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400">Deadline passed</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">Tidak ada dokumen.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
