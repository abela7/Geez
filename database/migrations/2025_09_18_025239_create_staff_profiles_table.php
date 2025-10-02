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
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('staff_id')->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            // Personal Information
            $table->text('address')->nullable();
            $table->json('emergency_contacts')->nullable()->comment('Array of emergency contacts with name, phone, relationship');
            $table->date('date_of_birth')->nullable();
            $table->string('photo_url')->nullable()->comment('URL to profile photo (S3, local storage, etc.)');

            // Employment Details
            $table->decimal('hourly_rate', 8, 2)->nullable()->comment('Hourly rate in local currency');
            $table->string('employee_id', 20)->nullable()->unique()->comment('Auto-generated employee ID (e.g., EMP-0001)');
            $table->text('notes')->nullable()->comment('HR notes, special requirements, etc.');

            // Audit Fields
            $table->foreignUlid('created_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('updated_by')->nullable()->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();

            // Indexes for performance
            $table->index('staff_id');
            $table->index('employee_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
