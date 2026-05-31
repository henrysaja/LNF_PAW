<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS ProsesPembaruanStatusKlaim;

            CREATE PROCEDURE ProsesPembaruanStatusKlaim(
                IN p_claim_id INT,
                IN p_status_baru VARCHAR(20),
                OUT p_kode_respon INT,
                OUT p_pesan_respon VARCHAR(255)
            )
            BEGIN
                DECLARE v_status_sekarang VARCHAR(20);
                DECLARE v_item_id INT;

                -- Ambil status klaim saat ini dan ID barangnya
                SELECT status_klaim, item_id INTO v_status_sekarang, v_item_id
                FROM claims WHERE id = p_claim_id;

                -- Evaluasi logika menggunakan CASE...END (Tanpa SIGNAL SQLSTATE)
                SET p_pesan_respon = CASE
                    WHEN p_status_baru NOT IN ('menunggu', 'diterima', 'ditolak')
                        THEN 'GAGAL: Nilai status klaim tidak valid.'
                    WHEN v_status_sekarang IN ('diterima', 'ditolak')
                        THEN 'GAGAL: Klaim ini sudah diproses dan tidak dapat diubah lagi.'
                    ELSE 'SUKSES'
                END;

                -- Tentukan kode respon
                SET p_kode_respon = CASE WHEN p_pesan_respon = 'SUKSES' THEN 1 ELSE 0 END;

                -- Eksekusi update jika lolos evaluasi
                IF p_kode_respon = 1 THEN
                    UPDATE claims SET status_klaim = p_status_baru WHERE id = p_claim_id;

                    IF p_status_baru = 'diterima' THEN
                        UPDATE items SET status = 'dikembalikan' WHERE id = v_item_id;
                        SET p_pesan_respon = 'SUKSES: Klaim diterima dan status barang telah diperbarui.';
                    ELSE
                        SET p_pesan_respon = 'SUKSES: Klaim berhasil ditolak.';
                    END IF;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS ProsesPembaruanStatusKlaim;");
    }
};
