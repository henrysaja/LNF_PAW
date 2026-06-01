@extends('layouts.app')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-6">
        <h3 class="fw-bold text-primary mb-3">Pengaturan Akun</h3>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label text-muted">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Nomor Pokok Mahasiswa (NPM)</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->npm }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted">Email Kampus</label>
                        <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    </div>

                    <hr class="mb-4">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nomor WhatsApp Aktif</label>
                        <input type="text" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp', $user->no_whatsapp) }}" required>
                        <div class="form-text">Pastikan nomor ini aktif agar mudah dihubungi saat serah terima barang.</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold">Ganti Password (Opsional)</label>
                            <div class="form-text text-muted mb-2">Biarkan kosong jika Anda tidak ingin mengganti password.</div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <input type="password" name="password" class="form-control" placeholder="Password Baru">
                        </div>
                        <div class="col-md-6">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password Baru">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
