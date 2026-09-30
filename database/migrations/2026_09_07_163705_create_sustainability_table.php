<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sustainability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('metric_type', ['carbon', 'electricity', 'water', 'waste']);
            $table->string('metric_name', 100);
            $table->decimal('value', 15, 2);
            $table->string('unit', 20);
            $table->date('measurement_date');
            $table->string('source', 100)->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('restrict');
            $table->text('notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sustainability');
    }
};