@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-danger">Pusat Komando Admin LFM</h2>
        <p class="text-muted">Kelola seluruh data kehilangan dan verifikasi klaim mahasiswa di sini.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 border-top border-danger border-4">
            <div class="card-header bg-white fw-bold">
                ⚠️ Antrean Verifikasi Klaim ({{ $antreanKlaim->count() }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Pengklaim</th>
                                <th>Barang</th>
                                <th>Bukti yang Dikirim</th>
                                <th>Tanggal</th>
                                <th class="text-end pe-4">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($antreanKlaim as $klaim)
                                <tr>
                                    <td class="ps-4">
                                        <strong>{{ $klaim->user->name }}</strong><br>
                                        <small class="text-muted">NPM: {{ $klaim->user->npm }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('items.show', $klaim->item_id) }}" class="text-decoration-none fw-bold">
                                            {{ $klaim->item->nama_barang }}
                                        </a>
                                    </td>
                                    <td>{{ $klaim->bukti_klaim }}</td>
                                    <td>{{ $klaim->created_at->format('d M Y') }}</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('claims.updateStatus', $klaim->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status_klaim" value="ditolak">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                                        </form>

                                        <form action="{{ route('claims.updateStatus', $klaim->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status_klaim" value="diterima">
                                            <button type="submit" class="btn btn-success btn-sm ms-1">Terima</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Hore! Tidak ada antrean klaim saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
