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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pkl_id')->constrained()->onDelete('cascade');
            $table->enum('report_type', ['daily', 'weekly', 'monthly', 'final'])->default('daily');
            $table->string('title');
            $table->text('content');
            $table->string('file_path')->nullable(); // untuk upload file laporan
            $table->json('attachments')->nullable(); // untuk multiple attachments
            $table->date('report_date');
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'approved', 'rejected'])->default('draft');
            $table->text('feedback')->nullable(); // feedback dari pembimbing
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
