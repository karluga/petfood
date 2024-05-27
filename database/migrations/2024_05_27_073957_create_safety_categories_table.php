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
        Schema::create('safety_categories', function (Blueprint $table) {
            $table->id();
            $table->string('safety_id');
            $table->string('array_key');
            $table->string('filename')->nullable();
            $table->string('language', 2);
            $table->string('name');
            $table->string('hex_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_categories');
    }
};
