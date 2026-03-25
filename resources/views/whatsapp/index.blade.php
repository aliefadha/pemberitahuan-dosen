<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fab fa-whatsapp mr-2 text-success"></i>{{ __('WhatsApp Connection') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status</h6>
                </div>
                <div class="card-body text-center">
                    <div id="status-container">
                        @if($isReady)
                            <div class="text-success mb-4">
                                <i class="fas fa-check-circle fa-4x"></i>
                                <h4 class="mt-3">WhatsApp Terhubung!</h4>
                                <p class="text-muted">Whatsapp Berhasil Terhubung</p>
                            </div>
                        @elseif($qrCode)
                            <div id="qr-container">
                                <h4 class="mb-3">Scan QR Code</h4>
                                <p class="text-muted mb-4">Buka Whatsapp dan scan qr ini.</p>
                                <img src="{{ $qrCode }}" alt="WhatsApp QR Code" class="img-fluid mb-4" style="max-width: 300px;">
                                <div class="small text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Klik "Refresh Status" untuk melihat status.
                                </div>
                            </div>
                        @else
                            <div class="text-warning mb-4">
                                <i class="fas fa-spinner fa-spin fa-4x"></i>
                                <h4 class="mt-3">Loading...</h4>
                                <p class="text-muted">Mohon tunggu.</p>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-primary" onclick="refreshStatus()">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh Status
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="restartService()">
                            <i class="fas fa-redo mr-1"></i> Restart
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Test WA</h6>
                </div>
                <div class="card-body">
                    <form id="test-form">
                        <div class="form-group">
                            <label for="test-phone">No Hp</label>
                            <input type="text" class="form-control" id="test-phone" placeholder="08xxxxxxxxx">
                            <small class="form-text text-muted">Masukkan nomor Hp</small>
                        </div>
                        <div class="form-group">
                            <label for="test-message">Pesan</label>
                            <textarea class="form-control" id="test-message" rows="3" placeholder="Test message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Test Pesan
                        </button>
                    </form>
                    <div id="test-result" class="mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function refreshStatus() {
            location.reload();
        }

        function restartService() {
            if (!confirm('Are you sure you want to restart the WhatsApp service?')) {
                return;
            }

            fetch('{{ route('admin.whatsapp.restart') }}', { 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    setTimeout(() => location.reload(), 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to restart service');
                });
        }

        function sendTestMessage(phone, message) {
            fetch('{{ route('admin.whatsapp.sendTest') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ phone, message })
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('test-result');
                resultDiv.classList.remove('d-none');
                resultDiv.innerHTML = data.success 
                    ? '<div class="alert alert-success"><i class="fas fa-check mr-1"></i> Message sent successfully!</div>'
                    : '<div class="alert alert-danger"><i class="fas fa-times mr-1"></i> Failed to send message</div>';
            })
            .catch(error => {
                console.error('Error:', error);
                const resultDiv = document.getElementById('test-result');
                resultDiv.classList.remove('d-none');
                resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
            });
        }

        document.getElementById('test-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const phone = document.getElementById('test-phone').value;
            const message = document.getElementById('test-message').value;
            
            if (!phone || !message) {
                alert('Please fill in both phone and message');
                return;
            }

            sendTestMessage(phone, message);
        });
    </script>
    @endpush
</x-app-layout>
