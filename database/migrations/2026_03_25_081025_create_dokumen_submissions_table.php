<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumens')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamp('tanggal_submit')->useCurrent();
            $table->timestamps();

            $table->unique(['dokumen_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_submissions');
    }
};
