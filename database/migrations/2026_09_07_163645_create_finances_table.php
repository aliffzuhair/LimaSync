<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('transaction_type', ['income', 'expense']);
            $table->string('category', 50);
            $table->string('description', 255);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('reference_no', 50)->nullable();
            $table->string('receipt_path', 255)->nullable();
            $table->foreignId('entered_by')->constrained('users')->onDelete('restrict');
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};