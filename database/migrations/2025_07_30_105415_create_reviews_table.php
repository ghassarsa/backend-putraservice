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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->longText('komentar');
            $table->decimal('rating', 2, 1);
            $table->enum('validasi', ['belum', 'sudah'])->default('belum');
            $table->string('device_token')->nullable();
            $table->enum('perubahan', ['tidak', 'ya'])->default('tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
