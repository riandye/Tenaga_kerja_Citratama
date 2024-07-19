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
        Schema::create('penerima_jadwal', function (Blueprint $table) {
            $table->id('ID_penerima');
            $table->unsignedBigInteger('ID_jadwal');
            $table->unsignedBigInteger('ID_user');
            $table->timestamps();

            $table->foreign('ID_jadwal')->references('ID_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('ID_User')->references('ID_user')->on('users')->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_jadwal');
    }
};
