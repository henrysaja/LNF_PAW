<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mengubah status menjadi VARCHAR(20) agar lebih fleksibel
        Schema::table('items', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });
    }

    public function down()
    {
        // Mengembalikan ke ENUM jika ingin rollback
        DB::statement("ALTER TABLE items MODIFY status ENUM('hilang', 'ditemukan')");
    }
};
