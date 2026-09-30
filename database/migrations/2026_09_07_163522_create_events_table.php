<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->string('event_name', 100);
            $table->string('event_type', 50);
            $table->text('description')->nullable();
            $table->string('venue', 200)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('estimated_attendees')->nullable()->default(0);
            $table->integer('actual_attendees')->nullable()->default(0);
            $table->enum('status', ['planning', 'in_progress', 'completed', 'cancelled'])->default('planning');
            $table->integer('progress_percentage')->default(0);
            $table->decimal('budget', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};