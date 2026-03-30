<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

return new class extends Migration
{
    public function up(): void
    
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refinery_id')->constrained()->cascadeOnDelete();
            
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->string('category'); 
            $table->enum('type', ['دائن', 'مدين']);
            $table->decimal('amount', 15, 2);
            
            $table->nullableMorphs('reference'); 

            $table->date('entry_date');
            $table->string('payment_method')->default('cash');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};