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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('ID_jadwal');
            $table->unsignedBigInteger('ID_mitra');
            $table->date('tanggal');
            $table->string('tempat');
            $table->string('jam');
            $table->timestamps();

            $table->foreign('ID_mitra')->references('ID_mitra')->on('perusahaan_mitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
