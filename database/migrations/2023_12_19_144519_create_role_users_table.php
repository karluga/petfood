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
        Schema::create('role_user', function (Blueprint $table) {
            // Define a foreign key for user_id that references the users table
            // When a user is deleted, associated entries in this table are also deleted
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
            // Define a foreign key for role_id that references the roles table
            // No cascading deletion is applied to prevent deletion of users if a role is deleted
            $table->foreignId('role_id')->constrained();
        
            // Define a composite primary key to ensure uniqueness of user_id and role_id combinations
            $table->primary(['user_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_users');
    }
};
