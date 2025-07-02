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
        Schema::create('pkls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // mahasiswa
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // dosen pembimbing
            $table->foreignId('field_supervisor_id')->nullable()->constrained('users')->onDelete('set null'); // pembimbing lapangan
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'ongoing', 'completed'])->default('pending');
            $table->text('description')->nullable();
            $table->json('documents')->nullable(); // menyimpan path dokumen yang diupload
            $table->text('rejection_reason')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->date('defense_date')->nullable(); // tanggal sidang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pkls');
    }
};
