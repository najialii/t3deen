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
        Schema::create('invoices', function (Blueprint $table) {
           $table->id();
    $table->string('invoice_number')->unique(); 
    $table->foreignId('refinery_id')->constrained();
    $table->foreignId('transaction_id')->constrained();
    $table->foreignId('customer_id')->constrained();
    
    $table->decimal('total_amount', 15, 2);
    $table->decimal('tax_amount', 15, 2)->default(0);
    $table->enum('status', ['unpaid', 'paid', 'overdue', 'cancelled'])->default('unpaid');
    
    $table->timestamp('issued_at');
    $table->timestamp('due_at')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
