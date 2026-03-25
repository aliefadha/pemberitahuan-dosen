<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-gray-800">
            <i class="fas fa-plus mr-2"></i>{{ __('Tambah Dokumen') }}
        </h2>
    </x-slot>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Dokumen</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dokumens.store') }}">
                @csrf

                <div class="form-group">
                    <label for="judul">Judul Dokumen</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" required autofocus>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tipe_dokumen">Tipe Dokumen</label>
                    <select class="form-control @error('tipe_dokumen') is-invalid @enderror" id="tipe_dokumen" name="tipe_dokumen" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="pdf" {{ old('tipe_dokumen') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="docx" {{ old('tipe_dokumen') == 'docx' ? 'selected' : '' }}>DOCX</option>
                    </select>
                    @error('tipe_dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_deadline">Tanggal Deadline</label>
                    <input type="datetime-local" class="form-control @error('tanggal_deadline') is-invalid @enderror" id="tanggal_deadline" name="tanggal_deadline" value="{{ old('tanggal_deadline') }}" required>
                    @error('tanggal_deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <a href="{{ route('admin.dokumens.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</x-app-layout>
