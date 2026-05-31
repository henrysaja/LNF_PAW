@extends('layouts.app')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <h4 class="fw-bold text-center mb-4 text-primary">Login LFM</h4>

                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Pokok Mahasiswa (NPM)</label>
                        <input type="text" name="npm" class="form-control" value="{{ old('npm') }}" placeholder="Contoh: 2428240002" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3 fw-bold shadow-sm">Masuk</button>
                    <p class="text-center text-muted mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
