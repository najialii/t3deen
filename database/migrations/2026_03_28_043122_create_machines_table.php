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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('unit', [    'KG' => 'كيلو جرام (KG)','SAG' => 'صاع (SAG)','Gram' => 'جرام (Gram)','Ton' => 'طن (Ton)','Ounce' => 'أونصة (Ounce)',]);
            $table->decimal('price_per_unit', 15, 4);
            $table->foreignId('refinery_id')->constrained('refineries')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};
