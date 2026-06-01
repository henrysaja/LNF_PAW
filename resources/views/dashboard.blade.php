@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-primary">Aktivitas Saya</h2>
        <p class="text-muted">Pantau laporan barang dan pengajuan klaim Anda di platform LFM.</p>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="laporan-tab" data-bs-toggle="tab" data-bs-target="#laporan-content" type="button" role="tab">
            📢 Laporan Saya ({{ $laporanSaya->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="klaim-tab" data-bs-toggle="tab" data-bs-target="#klaim-content" type="button" role="tab">
            💼 Klaim Saya ({{ $klaimSaya->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="dashboardTabsContent">

    <div class="tab-pane fade show active" id="laporan-content" role="tabpanel">
        <div class="card shadow-sm border-0 p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Lokasi</th>
                            <th>Status Barang</th>
                            <th>Tanggal Lapor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanSaya as $laporan)
                            <tr>
                                <td class="fw-bold">{{ $laporan->nama_barang }}</td>
                                Likasi<td>{{ $laporan->lokasi_ditemukan_atau_hilang }}</td>
                                <td>
                                    <span class="badge bg-{{ $laporan->status == 'hilang' ? 'danger' : ($laporan->status == 'ditemukan' ? 'success' : 'secondary') }}">
                                        {{ strtoupper($laporan->status) }}
                                    </span>
                                </td>
                                <td>{{ $laporan->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('items.show', $laporan->id) }}" class="btn btn-outline-primary btn-sm">
                                        Lihat & Kelola Klaim
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Anda belum pernah membuat laporan barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="klaim-content" role="tabpanel">
        <div class="card shadow-sm border-0 p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Barang yang Diklaim</th>
                            <th>Bukti yang Anda Kirim</th>
                            <th>Status Klaim</th>
                            <th>Tanggal Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($klaimSaya as $klaim)
                            <tr>
                                <td class="fw-bold">{{ $klaim->item->nama_barang }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($klaim->bukti_klaim, 50) }}</td>
                                <td>
                                    <span class="badge bg-{{ $klaim->status_klaim == 'menunggu' ? 'warning text-dark' : ($klaim->status_klaim == 'diterima' ? 'success' : 'danger') }}">
                                        {{ strtoupper($klaim->status_klaim) }}
                                    </span>
                                </td>
                                <td>{{ $klaim->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('items.show', $klaim->item_id) }}" class="btn btn-light btn-sm">
                                        Lihat Detail Barang
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Anda belum pernah mengajukan klaim barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
