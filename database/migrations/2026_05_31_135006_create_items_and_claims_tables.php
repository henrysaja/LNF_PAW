<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Penyesuaian tabel users bawaan Laravel untuk mendukung NPM dan kontak
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'npm')) {
                $table->string('npm', 15)->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'no_whatsapp')) {
                $table->string('no_whatsapp', 15)->nullable()->after('npm');
            }
        });

        // 2. Tabel items (Laporan Barang)
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pelapor
            $table->string('nama_barang');
            $table->text('deskripsi');
            $table->string('lokasi_ditemukan_atau_hilang');
            $table->enum('status', ['hilang', 'ditemukan', 'dikembalikan'])->default('hilang');
            $table->timestamps();
        });

        // 3. Tabel claims (Pengajuan Klaim Kepemilikan)
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pengklaim
            $table->text('bukti_klaim');
            $table->enum('status_klaim', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Urutan drop harus dibalik dari proses pembuatan untuk menghindari error foreign key
        Schema::dropIfExists('claims');
        Schema::dropIfExists('items');

        // Hapus kolom tambahan di tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npm', 'no_whatsapp']);
        });
    }
};
