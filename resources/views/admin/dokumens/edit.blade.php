<x-app-layout>
    <x-slot name="header">
        <i class="fas fa-edit mr-2"></i>{{ __('Edit Dokumen') }}
    </x-slot>

    <div class="card">
        <div class="card-header">Form Edit Dokumen</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dokumens.update', $dokumen) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <x-input-label for="judul" value="Judul Dokumen" />
                    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" :value="old('judul', $dokumen->judul)" required autofocus />
                    <x-input-error :messages="$errors->get('judul')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="deskripsi" value="Deskripsi" />
                    <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('deskripsi') border-red-300 text-red-900 @enderror">{{ old('deskripsi', $dokumen->deskripsi) }}</textarea>
                    <x-input-error :messages="$errors->get('deskripsi')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="tipe_dokumen" value="Tipe Dokumen" />
                    <select id="tipe_dokumen" name="tipe_dokumen" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('tipe_dokumen') border-red-300 text-red-900 @enderror">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="pdf" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="docx" {{ old('tipe_dokumen', $dokumen->tipe_dokumen) == 'docx' ? 'selected' : '' }}>DOCX</option>
                    </select>
                    <x-input-error :messages="$errors->get('tipe_dokumen')" class="mt-1" />
                </div>

                <div class="mb-5">
                    <x-input-label for="tanggal_deadline" value="Tanggal Deadline" />
                    <x-text-input id="tanggal_deadline" name="tanggal_deadline" type="datetime-local" class="mt-1 block w-full" :value="old('tanggal_deadline', $dokumen->tanggal_deadline->format('Y-m-d\TH:i'))" required />
                    <x-input-error :messages="$errors->get('tanggal_deadline')" class="mt-1" />
                </div>

                <hr class="my-6 border-gray-200">

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.dokumens.index') }}" class="btn-secondary">Kembali</a>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
