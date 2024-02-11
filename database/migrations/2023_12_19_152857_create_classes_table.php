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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->integer('gbif_class_id'); // Amphibians, Aves, Reptiles, etc.
            $table->string('class'); // Amphibians
            $table->string('emoji'); // 🐸
            // https://api.gbif.org/v1/species/131
            $table->string('hex_color');
            // 3698B7
            $table->string('description');
            // Amphibians will pretty much eat anything live that they can fit in their mouths! 
            // This includes bugs, slugs, snails, other frogs, spiders, worms, mice or even
            // birds and bats (if the frog is big enough and the bird or bat small enough).
            $table->tinyInteger('on_display')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
