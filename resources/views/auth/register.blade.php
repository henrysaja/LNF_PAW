@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-center text-primary">Daftar Akun LFM</h4>
                <p class="text-center text-muted mb-4">Portal Lost & Found Universitas Multi Data Palembang</p>

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Pokok Mahasiswa (NPM)</label>
                        <input type="text" name="npm" class="form-control" value="{{ old('npm') }}" placeholder="Contoh: 2428240002" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Kampus</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@mhs.mdp.ac.id" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nomor WhatsApp</label>
                        <input type="text" name="no_whatsapp" class="form-control" value="{{ old('no_whatsapp') }}" placeholder="Contoh: 081100001111" required>
                        <div class="form-text text-muted">
                            <small>Nomor ini akan dibagikan secara otomatis kepada pelapor jika klaim Anda diterima.</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-bold shadow-sm">Daftar Sekarang</button>

                    <p class="text-center text-muted mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Masuk di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
