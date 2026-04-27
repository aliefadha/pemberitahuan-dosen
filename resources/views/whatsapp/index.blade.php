<x-app-layout>
    <x-slot name="header">
        <i class="fab fa-whatsapp mr-2 text-emerald-500"></i>{{ __('WhatsApp Connection') }}
    </x-slot>

    <div class="mx-auto max-w-lg space-y-6">
        {{-- Status Card --}}
        <div class="card">
            <div class="card-header">Status</div>
            <div class="card-body text-center">
                @if($isReady)
                    <div class="text-emerald-500 mb-4">
                        <i class="fas fa-check-circle text-5xl"></i>
                        <h4 class="mt-3 text-lg font-semibold text-gray-900">WhatsApp Terhubung!</h4>
                        <p class="text-sm text-gray-500">Whatsapp Berhasil Terhubung</p>
                    </div>
                @elseif($qrCode)
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Scan QR Code</h4>
                        <p class="text-sm text-gray-500 mb-4">Buka Whatsapp dan scan qr ini.</p>
                        <img src="{{ $qrCode }}" alt="WhatsApp QR Code" class="mx-auto mb-4 max-w-[300px]">
                        <p class="text-xs text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i> Klik "Refresh Status" untuk melihat status.
                        </p>
                    </div>
                @else
                    <div class="text-amber-500 mb-4">
                        <i class="fas fa-spinner fa-spin text-5xl"></i>
                        <h4 class="mt-3 text-lg font-semibold text-gray-900">Loading...</h4>
                        <p class="text-sm text-gray-500">Mohon tunggu.</p>
                    </div>
                @endif

                <hr class="my-5 border-gray-200">

                <div class="flex justify-center gap-3">
                    <button type="button" class="btn-secondary btn-sm" onclick="refreshStatus()">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh Status
                    </button>
                    <button type="button" class="btn-warning btn-sm" onclick="restartService()">
                        <i class="fas fa-redo mr-1"></i> Restart
                    </button>
                </div>
            </div>
        </div>

        {{-- Test WhatsApp Card --}}
        <div class="card">
            <div class="card-header">Test WA</div>
            <div class="card-body">
                <form id="test-form">
                    <div class="mb-4">
                        <label for="test-phone" class="block text-sm font-medium text-gray-700">No Hp</label>
                        <input type="text" id="test-phone" class="form-input-custom mt-1 block w-full" placeholder="08xxxxxxxxx">
                        <p class="mt-1 text-xs text-gray-400">Masukkan nomor Hp</p>
                    </div>
                    <div class="mb-4">
                        <label for="test-message" class="block text-sm font-medium text-gray-700">Pesan</label>
                        <textarea id="test-message" rows="3" class="form-input-custom mt-1 block w-full" placeholder="Test message"></textarea>
                    </div>
                    <button type="submit" class="btn-success w-full">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Test Pesan
                    </button>
                </form>
                <div id="test-result" class="mt-4 hidden"></div>
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
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = data.success
                    ? '<div class="rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700"><i class="fas fa-check mr-1"></i> Message sent successfully!</div>'
                    : '<div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700"><i class="fas fa-times mr-1"></i> Failed to send message</div>';
            })
            .catch(error => {
                console.error('Error:', error);
                const resultDiv = document.getElementById('test-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = '<div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">Error: ' + error.message + '</div>';
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
