<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                // TODO 1: Tambahkan kolom 'nilai' dan 'catatan_dosen' setelah kolom 'jawaban'
                $table->unsignedTinyInteger('nilai')->nullable()->after('jawaban');
                $table->text('catatan_dosen')->nullable()->after('nilai');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('submissions')) {
            Schema::table('submissions', function (Blueprint $table) {
                // TODO 1 (Rollback): Hapus kolom 'nilai' dan 'catatan_dosen'
                $table->dropColumn(['nilai', 'catatan_dosen']);
            });
        }
    }
};