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
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
            $table->foreignId('refinery_id')->constrained('refineries')->cascadeOnDelete();
            
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            
            $table->foreignId('machine_id')->constrained('machines')->restrictOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->restrictOnDelete();
            $table->foreignId('sales_manager_id')->constrained('users')->restrictOnDelete();
            
            $table->string('unit'); 
            $table->decimal('quantity', 15, 4);
            $table->decimal('price_per_unit', 15, 4);
            
            $table->decimal('total_amount', 15, 4)->storedAs('quantity * price_per_unit');
            
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
