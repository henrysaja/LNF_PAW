@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold text-primary">Daftar Laporan Barang</h2>
        @auth
            <a href="{{ route('items.create') }}" class="btn btn-primary shadow-sm">+ Lapor Barang</a>
        @endauth
        @guest
            <a href="{{ route('login') }}" class="btn btn-outline-primary shadow-sm">Masuk untuk Lapor</a>
        @endguest
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">🔍</span>
                            <input type="text" id="search-input" class="form-control border-start-0" placeholder="Ketik nama barang, lokasi, atau deskripsi untuk mencari langsung...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="status-filter" class="form-select">
                            <option value="semua">Semua Status</option>
                            <option value="hilang">Barang Hilang</option>
                            <option value="ditemukan">Barang Ditemukan</option>
                            <option value="dikembalikan">Sudah Dikembalikan</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="item-results-container">
    @include('items.partials._item_list')
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const resultsContainer = document.getElementById('item-results-container');

        let typingTimer;
        const typingInterval = 300;

        function fetchResults() {
            const keyword = searchInput.value;
            const status = statusFilter.value;

            // Membangun URL dengan parameter pencarian
            const url = `{{ route('items.index') }}?search=${encodeURIComponent(keyword)}&status=${encodeURIComponent(status)}`;

            // Mengirim request ke server (menyamar sebagai AJAX)
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Mengganti isi kontainer dengan hasil HTML yang baru
                resultsContainer.innerHTML = html;
            })
            .catch(error => console.error('Error fetching data:', error));
        }

        // Event Listener: Berjalan setiap kali mahasiswa mengetik di kolom pencarian
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(fetchResults, typingInterval);
        });

        // Event Listener: Berjalan setiap kali mahasiswa mengubah dropdown status
        statusFilter.addEventListener('change', fetchResults);
    });
</script>
@endsection
