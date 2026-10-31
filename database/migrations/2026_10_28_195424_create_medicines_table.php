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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('generic_name')->index();
            $table->string('category')->index();
            $table->string('dosage_form');
            $table->string('strength');
            $table->string('batch_number')->unique();
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(25);
            $table->string('unit')->default('tablets');
            $table->date('expiry_date')->index();
            $table->string('manufacturer')->nullable();
            $table->string('instructions_default')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
