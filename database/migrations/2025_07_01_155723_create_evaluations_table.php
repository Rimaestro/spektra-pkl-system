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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkl_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade'); // yang menilai (dosen/pembimbing lapangan)
            $table->enum('evaluator_type', ['supervisor', 'field_supervisor']); // jenis penilai
            $table->decimal('technical_score', 5, 2)->nullable(); // nilai teknis
            $table->decimal('attitude_score', 5, 2)->nullable(); // nilai sikap
            $table->decimal('communication_score', 5, 2)->nullable(); // nilai komunikasi
            $table->decimal('final_score', 5, 2)->nullable(); // nilai akhir
            $table->text('comments')->nullable();
            $table->text('suggestions')->nullable();
            $table->enum('status', ['draft', 'submitted', 'final'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
