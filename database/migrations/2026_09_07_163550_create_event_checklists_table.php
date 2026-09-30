<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'not_applicable'])->default('pending');
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'checklist_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_checklists');
    }
};