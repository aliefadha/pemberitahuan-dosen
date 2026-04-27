<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-file-alt mr-2"></i>{{ __('Kelola Dokumen') }}
    </x-slot>

    <div class="card">
        <div class="card-header flex flex-wrap items-center justify-between gap-3">
            <span>Daftar Dokumen</span>
            <a href="{{ route('admin.dokumens.create') }}" class="btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Dokumen
            </a>
        </div>
        <div class="card-body !p-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Pengumpulan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($dokumens as $dokumen)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-600">{{ $loop->iteration + ($dokumens->currentPage() - 1) * $dokumens->perPage() }}</td>
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
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.dokumens.submissions', $dokumen) }}" class="btn-primary btn-sm">
                                    <i class="fas fa-eye mr-1"></i> {{ $dokumen->submission_count }} / {{ \App\Models\User::where('role', 'dosen')->count() }} pengumpulan
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.dokumens.edit', $dokumen) }}" class="btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.dokumens.destroy', $dokumen) }}" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $dokumens->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
