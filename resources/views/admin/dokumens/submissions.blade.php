<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-file-alt mr-2"></i>{{ __('Submission: ') }}{{ $dokumen->judul }}
    </x-slot>

    <div class="card">
        <div class="card-header flex flex-wrap items-center justify-between gap-3">
            <span>Detail Dokumen</span>
            @php
                $allDosen = \App\Models\User::where('role', 'dosen')->get();
                $unsubmittedCount = $allDosen->filter(fn($d) => !$submissions->where('user_id', $d->id)->first() && $d->no_telepon)->count();
            @endphp
            @if($unsubmittedCount > 0)
                <button type="button" class="btn-success btn-sm" onclick="sendBatchWhatsApp()">
                    <i class="fab fa-whatsapp mr-1"></i> Kirim WhatsApp
                </button>
            @endif
        </div>
        <div class="card-body">
            <table class="mb-6 text-sm">
                <tbody>
                    <tr>
                        <td class="pb-1 pr-4"><strong>Judul</strong></td>
                        <td class="pb-1 pr-2"><strong>:</strong></td>
                        <td class="pb-1">{{ $dokumen->judul }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4"><strong>Deskripsi</strong></td>
                        <td class="pb-1 pr-2"><strong>:</strong></td>
                        <td class="pb-1">{{ $dokumen->deskripsi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4"><strong>Tipe</strong></td>
                        <td class="pb-1 pr-2"><strong>:</strong></td>
                        <td class="pb-1">{{ strtoupper($dokumen->tipe_dokumen) }}</td>
                    </tr>
                    <tr>
                        <td class="pb-1 pr-4"><strong>Deadline</strong></td>
                        <td class="pb-1 pr-2"><strong>:</strong></td>
                        <td class="pb-1">
                            {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                            <span class="badge ml-2 {{ $dokumen->isDeadlinePassed() ? 'badge-secondary' : 'badge-success' }}">
                                {{ $dokumen->isDeadlinePassed() ? 'Expired' : 'Aktif' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Nama Dosen</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No. HP</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Tgl Submit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">File</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach($allDosen as $dosen)
                        @php $submission = $submissions->where('user_id', $dosen->id)->first(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $dosen->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $dosen->email }}</td>
                            <td class="px-4 py-3">
                                @if($dosen->no_telepon)
                                    <span class="text-emerald-600">{{ $dosen->no_telepon }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
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
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                @if($submission) {{ $submission->tanggal_submit->format('d/m/Y H:i') }} @else - @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($submission)
                                    @foreach($submission->files as $file)
                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="btn-info btn-sm mb-1" title="{{ $file->original_name }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endforeach
                                    @if($submission->files->isEmpty())
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($submission && $submission->isPending())
                                    <div class="flex flex-col gap-1.5">
                                        <form method="POST" action="{{ route('admin.dokumens.submissions.accept', [$dokumen, $submission->id]) }}">
                                            @csrf
                                            <input type="hidden" name="catatan" value="">
                                            <button type="submit" class="btn-success btn-sm w-full">
                                                <i class="fas fa-check mr-1"></i> Accept
                                            </button>
                                        </form>
                                        <button type="button" class="btn-danger btn-sm w-full" x-data
                                            x-on:click="$dispatch('open-modal', 'reject-modal-{{ $submission->id }}')">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </div>

                                    {{-- Reject Modal --}}
                                    <div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail === 'reject-modal-{{ $submission->id }}') show = true" x-on:close-modal.window="if ($event.detail === 'reject-modal-{{ $submission->id }}') show = false">
                                        <div x-show="show" x-cloak class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
                                            <div x-show="show" class="fixed inset-0 bg-gray-500/75" x-on:click="show = false"></div>
                                            <div x-show="show" class="relative z-50 mx-auto max-w-md rounded-xl bg-white shadow-xl">
                                                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                                    <h3 class="text-lg font-semibold text-gray-900">Tolak Submission</h3>
                                                    <button class="text-gray-400 hover:text-gray-600" x-on:click="show = false">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.dokumens.submissions.reject', [$dokumen, $submission->id]) }}">
                                                    @csrf
                                                    <div class="px-6 py-4">
                                                        <label for="catatan-{{ $submission->id }}" class="block text-sm font-medium text-gray-700">Catatan</label>
                                                        <textarea id="catatan-{{ $submission->id }}" name="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
                                                    </div>
                                                    <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                                                        <button type="button" x-on:click="show = false" class="btn-secondary">Batal</button>
                                                        <button type="submit" class="btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="my-6 border-gray-200">
            <a href="{{ route('admin.dokumens.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
        function sendBatchWhatsApp() {
            const message = `*Assalamualaikum Bapak/Ibu, semoga selalu berada dalam lindungan Allah SWT.*\n\nMohon untuk segera mengumpulkan dokumen "{{ $dokumen->judul }}" sebelum deadline. Silakan login ke aplikasi untuk men-submit dokumen Anda. Terima kasih.`;

            const recipients = [];
            @foreach($allDosen as $dosen)
                @if(!$submissions->where('user_id', $dosen->id)->first() && $dosen->no_telepon)
                    recipients.push({ phone: '{{ $dosen->no_telepon }}', name: '{{ $dosen->name }}' });
                @endif
            @endforeach

            if (recipients.length === 0) {
                alert('Tidak ada dosen yang belum submit dan memiliki nomor WhatsApp');
                return;
            }

            if (!confirm(`Kirim pesan ke dosen yang belum submit?`)) {
                return;
            }

            let successCount = 0;
            let failCount = 0;

            recipients.forEach((recipient, index) => {
                fetch('{{ route('admin.whatsapp.sendTest') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ phone: recipient.phone, message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) successCount++;
                    else failCount++;
                })
                .catch(() => failCount++)
                .finally(() => {
                    if (index === recipients.length - 1) {
                        setTimeout(() => {
                            alert(`Selesai! Berhasil: ${successCount}, Gagal: ${failCount}`);
                        }, 500);
                    }
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
