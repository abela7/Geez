<?php

declare(strict_types=1);

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
        Schema::create('staff', function (Blueprint $table) {
            // Primary Key - Using ULID for offline/online sync compatibility
            $table->ulid('id')->primary();
            
            // Basic Information
            $table->string('first_name')->comment('Staff member first name');
            $table->string('last_name')->comment('Staff member last name');
            $table->string('username')->unique()->comment('Unique username for login');
            $table->string('password')->comment('Hashed password');
            
            // Staff Type Relationship
            $table->foreignUlid('staff_type_id')->constrained('staff_types')->cascadeOnUpdate()->restrictOnDelete();
            
            // Contact Information
            $table->string('email')->nullable()->comment('Email address (optional for now)');
            $table->string('phone')->nullable()->comment('Phone number');
            
            // Employment Information
            $table->date('hire_date')->nullable()->comment('Date of employment');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->comment('Account status');
            
            // Authentication Tracking
            $table->timestamp('last_login_at')->nullable()->comment('Last login timestamp');
            $table->string('last_login_ip', 45)->nullable()->comment('Last login IP address');
            
            // Laravel Auth Fields
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['username']);
            $table->index(['staff_type_id']);
            $table->index(['status']);
            $table->index(['last_login_at']);
            $table->index(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};