<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_analyses', function (Blueprint $table) {
            $table->id();
            // Menyimpan path file gambar/video (nullable karena kadang user cuma masukin teks/link)
            $table->string('file_path')->nullable(); 
            
            // Menyimpan sumber tebakan: 'file', 'text', atau 'link'
            $table->string('type'); 
            
            // Hasil tebakan dari Gemini AI (ASLI / PALSU / BUKAN LOWONGAN)
            $table->string('result'); 
            
            // Kunci jawaban asli untuk evaluasi (nullable karena nanti lu isi manual buat validasi)
            $table->string('actual')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_analyses');
    }
};
