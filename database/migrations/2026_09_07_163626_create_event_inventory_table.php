<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('inventory_id')->constrained('inventory')->onDelete('restrict');
            $table->integer('quantity_allocated');
            $table->integer('quantity_used')->default(0);
            $table->integer('quantity_returned')->default(0);
            $table->enum('status', ['allocated', 'in_use', 'returned', 'lost'])->default('allocated');
            $table->text('notes')->nullable();
            $table->foreignId('allocated_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_inventory');
    }
};