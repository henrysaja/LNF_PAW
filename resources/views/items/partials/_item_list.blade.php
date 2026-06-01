<div class="row">
    @forelse($items as $item)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    @if ($item->foto_barang)
                        <img src="{{ asset('storage/' . $item->foto_barang) }}" class="card-img-top"
                            alt="Foto {{ $item->nama_barang }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted"
                            style="height: 200px;">
                            <span>🚫 Tidak ada foto</span>
                        </div>
                    @endif

                    <h5 class="card-title fw-bold">{{ $item->nama_barang }}</h5>
                    <p class="card-text text-muted mb-2">
                        <small>📍 Lokasi: {{ $item->lokasi_ditemukan_atau_hilang }}</small>
                    </p>
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($item->deskripsi, 80) }}</p>

                    <div class="mb-3">
                        @if ($item->status === 'hilang')
                            <span class="badge bg-danger px-3 py-2">Hilang</span>
                        @elseif($item->status === 'ditemukan')
                            <span class="badge bg-success px-3 py-2">Ditemukan</span>
                        @elseif($item->status === 'dikembalikan')
                            <span class="badge bg-secondary px-3 py-2">Dikembalikan</span>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pb-3">
                    @if ($item->status !== 'dikembalikan')
                        <a href="{{ route('items.show', $item->id) }}"
                            class="btn btn-outline-primary btn-sm w-100">Ajukan Klaim / Lihat Detail</a>
                    @else
                        <button class="btn btn-outline-secondary btn-sm w-100" disabled>Kasus Selesai</button>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <h5 class="text-muted">Tidak ada laporan barang yang ditemukan.</h5>
        </div>
    @endforelse
</div>
