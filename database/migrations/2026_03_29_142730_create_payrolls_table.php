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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('refinery_id')->constrained()->cascadeOnDelete();
            
         
            $table->morphs('payable'); 

            $table->date('period_start')->comment('بداية فترة الراتب');
            $table->date('period_end')->comment('نهاية فترة الراتب');
            $table->decimal('base_salary', 15, 2)->default(0)->comment('الراتب الأساسي');
            $table->decimal('commissions', 15, 2)->default(0)->comment('العمولات');
            $table->decimal('bonuses', 15, 2)->default(0)->comment('الحوافز');
            $table->decimal('deductions', 15, 2)->default(0)->comment('الخصومات / السلفيات');
            
            $table->decimal('net_pay', 15, 2)->comment('صافي الراتب');
            
            // Metadata
            $table->string('payment_method')->default('cash')->comment('طريقة الدفع');
            $table->enum('status', ['draft', 'paid', 'cancelled'])->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};