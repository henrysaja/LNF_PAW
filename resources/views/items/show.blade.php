@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">Detail Laporan</div>
            <div class="card-body">
                <h4>{{ $item->nama_barang }}</h4>
                <p class="text-muted mb-1">Dilaporkan oleh: <strong>{{ $item->user->name }}</strong></p>
                <p class="text-muted">Status:
                    <span class="badge bg-{{ $item->status == 'hilang' ? 'danger' : ($item->status == 'ditemukan' ? 'success' : 'secondary') }}">
                        {{ strtoupper($item->status) }}
                    </span>
                </p>
                <hr>
                <h6>Lokasi:</h6>
                <p>{{ $item->lokasi_ditemukan_atau_hilang }}</p>
                <h6>Deskripsi:</h6>
                <p>{{ $item->deskripsi }}</p>
            </div>
        </div>

        @if($item->status !== 'dikembalikan')
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-primary text-white fw-bold">Ajukan Klaim</div>
            <div class="card-body">
                <form action="{{ route('claims.store', $item->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Simulasi Pengklaim:</label>
                        <select class="form-select" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($users as $user)
                                @if($user->id !== $item->user_id) <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bukti Kepemilikan (Ciri khusus):</label>
                        <textarea name="bukti_klaim" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-7">
        <h4 class="mb-3">Daftar Pengajuan Klaim</h4>
        @forelse($item->claims as $claim)
            <div class="card shadow-sm border-0 mb-3 {{ $claim->status_klaim == 'diterima' ? 'border-success border-2' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">{{ $claim->user->name }}</h6>
                        <span class="badge bg-{{ $claim->status_klaim == 'menunggu' ? 'warning' : ($claim->status_klaim == 'diterima' ? 'success' : 'danger') }}">
                            {{ strtoupper($claim->status_klaim) }}
                        </span>
                    </div>
                    <p class="mb-2"><strong>Bukti:</strong> {{ $claim->bukti_klaim }}</p>

                    @if($claim->status_klaim == 'diterima')
                        <div class="alert alert-success p-2 mb-0 mt-3">
                            <strong>Kontak Terbuka:</strong> Silakan hubungi via WhatsApp di
                            <a href="https://wa.me/{{ $claim->user->no_whatsapp }}" target="_blank" class="fw-bold text-success text-decoration-none">
                                {{ $claim->user->no_whatsapp }}
                            </a> untuk serah terima barang.
                        </div>
                    @endif
                </div>

                @if($item->status !== 'dikembalikan' && $claim->status_klaim == 'menunggu')
                <div class="card-footer bg-white border-0 text-end">
                    <form action="{{ route('claims.updateStatus', $claim->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status_klaim" value="ditolak">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                    </form>

                    <form action="{{ route('claims.updateStatus', $claim->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status_klaim" value="diterima">
                        <button type="submit" class="btn btn-success btn-sm ms-2">Terima Klaim</button>
                    </form>
                </div>
                @endif
            </div>
        @empty
            <div class="alert alert-light text-center border-0 shadow-sm">Belum ada yang mengajukan klaim untuk barang ini.</div>
        @endforelse
    </div>
</div>
@endsection
