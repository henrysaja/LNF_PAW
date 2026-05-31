@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                Buat Laporan Barang
            </div>
            <div class="card-body p-4">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pelapor (Simulasi Mahasiswa)</label>
                        <select class="form-select" required>
                            <option value="">-- Pilih Mahasiswa Pelapor --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Laporan (Status)</label>
                        <select name="status" class="form-select" required>
                            <option value="hilang">Saya Kehilangan Barang</option>
                            <option value="ditemukan">Saya Menemukan Barang</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Dompet Hitam / Kunci Motor" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Hilang / Ditemukan</label>
                        <input type="text" name="lokasi_ditemukan_atau_hilang" class="form-control" placeholder="Contoh: Kantin lantai 1 / Ruang Lab Komputer" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Tambahan</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan ciri-ciri barang secara detail..." required></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('items.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
