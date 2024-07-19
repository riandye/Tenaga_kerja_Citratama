<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recruitment', function (Blueprint $table) {
            $table->id('ID_recruitment');
            $table->unsignedBigInteger('ID_user');
            $table->unsignedBigInteger('ID_mitra');
            $table->date('tgl_recruitment');
            $table->enum('status', ['tersedia', 'menunggu', 'tidak tersedia'])->default('tersedia');
            $table->enum('info', ['diterima', 'menunggu','ditolak'])->nullable();
            $table->enum('info_penerimaan', ['diterima', 'menunggu','ditolak'])->nullable();
            $table->timestamps();

            $table->foreign('ID_user')->references('ID_user')->on('users')->onDelete('cascade');
            $table->foreign('ID_mitra')->references('ID_mitra')->on('perusahaan_mitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitment');
    }
};
