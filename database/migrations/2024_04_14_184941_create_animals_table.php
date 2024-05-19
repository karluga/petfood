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
        Schema::create('animals', function (Blueprint $table) {
            // there are 2 id's because entries are in different languages
            $table->id();
            $table->string('gbif_id')->unique();
            $table->string('language', 2);
            $table->string('slug');
            $table->string('name');
            $table->enum('category', ['wild', 'tame/domestic', 'exotic']);
            $table->enum('tier', [
                'GENUS',
                'FAMILY',
                'ORDER',
                'CLASS',
                'SUPERCLASS',
                'INFRAPHYLUM',
                'SUBPHYLUM',
                'PHYLUM',
                'INFRAKINGDOM',
                'SUBKINGDOM',
                'KINGDOM'
            ]);
            $table->text('appearance');
            $table->text('food');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
