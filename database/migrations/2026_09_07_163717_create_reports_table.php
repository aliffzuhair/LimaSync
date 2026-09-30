<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('report_type', ['event_summary', 'financial', 'sustainability', 'iso', 'final']);
            $table->string('report_title', 200);
            $table->string('file_path', 255)->nullable();
            $table->foreignId('generated_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('generated_at')->useCurrent();
            $table->boolean('client_viewed')->default(false);
            $table->timestamp('client_viewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};