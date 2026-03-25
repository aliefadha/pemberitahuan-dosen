<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-file-alt mr-2"></i>{{ __('Submission: ') }}{{ $dokumen->judul }}
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
            <h6 class="m-0 font-weight-bold text-primary">
                Detail Dokumen 
            </h6>
            @php
                $allDosen = \App\Models\User::where('role', 'dosen')->get();
                $unsubmittedCount = $allDosen->filter(fn($d) => !$submissions->where('user_id', $d->id)->first() && $d->no_telepon)->count();
            @endphp
            @if($unsubmittedCount > 0)
                <button type="button" class="btn btn-success btn-sm" onclick="sendBatchWhatsApp()">
                    <i class="fab fa-whatsapp mr-1"></i> Kirim WhatsApp
                </button>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4">
                <strong>Judul:</strong> {{ $dokumen->judul }}<br>
                <strong>Deskripsi:</strong> {{ $dokumen->deskripsi ?? '-' }}<br>
                <strong>Tipe:</strong> {{ strtoupper($dokumen->tipe_dokumen) }}<br>
                <strong>Deadline:</strong> {{ $dokumen->tanggal_deadline->format('d/m/Y H:i') }}
                @if($dokumen->isDeadlinePassed())
                    <span class="badge badge-secondary">Expired</span>
                @else
                    <span class="badge badge-success">Aktif</span>
                @endif
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Dosen</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Tanggal Submit</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allDosen as $dosen)
                        @php
                            $submission = $submissions->where('user_id', $dosen->id)->first();
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $dosen->name }}</td>
                            <td>{{ $dosen->email }}</td>
                            <td>
                                @if($dosen->no_telepon)
                                    <span class="text-success">{{ $dosen->no_telepon }}</span>
                                @else
                                    <span class="text-muted">-</span>
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
                                @if($submission)
                                    {{ $submission->tanggal_submit->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($submission)
                                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($submission && $submission->isPending())
                                    <div class="d-flex flex-column gap-1">
                                        <form method="POST" action="{{ route('admin.dokumens.submissions.accept', [$dokumen, $submission->id]) }}">
                                            @csrf
                                            <input type="hidden" name="catatan" value="">
                                            <button type="submit" class="btn btn-success btn-sm btn-block">
                                                <i class="fas fa-check mr-1"></i> Accept
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#rejectModal{{ $submission->id }}">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </div>

                                    <div class="modal fade" id="rejectModal{{ $submission->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Tolak Submission</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST" action="{{ route('admin.dokumens.submissions.reject', [$dokumen, $submission->id]) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="catatan">Catatan</label>
                                                            <textarea class="form-control" name="catatan" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>
            <a href="{{ route('admin.dokumens.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    @push('scripts')
    <script>
        function sendBatchWhatsApp() {
            const message = `Halo! untuk segera mengumpulkan dokumen "{{ $dokumen->judul }}" sebelum deadline. Mohon segera upload dokumen Anda. Terima kasih.`;
            
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
