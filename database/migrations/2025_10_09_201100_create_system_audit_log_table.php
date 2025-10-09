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
        // Guard against re-running migration
        if (Schema::hasTable('system_audit_log')) {
            return;
        }

        Schema::create('system_audit_log', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // What was changed
            $table->string('table_name', 100)->comment('Database table name');
            $table->ulid('record_id')->comment('ID of the affected record');
            
            // Action performed
            $table->enum('action', [
                'create',
                'update',
                'delete',
                'approve',
                'reject',
                'pay',
                'cancel',
                'recalculate',
                'export',
                'view',
                'other'
            ])->comment('Action that was performed');
            
            // Change Details
            $table->json('old_values')->nullable()->comment('Values before change');
            $table->json('new_values')->nullable()->comment('Values after change');
            $table->json('changed_fields')->nullable()->comment('List of fields that changed');
            
            // Who & When
            $table->foreignUlid('performed_by')
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete()
                ->comment('Staff member who performed the action');
            $table->timestamp('performed_at')->useCurrent()->comment('When action was performed');
            
            // Request Context
            $table->string('ip_address', 45)->nullable()->comment('IP address of user');
            $table->text('user_agent')->nullable()->comment('Browser user agent');
            $table->string('request_method', 10)->nullable()->comment('HTTP method (GET, POST, etc.)');
            $table->string('request_url', 500)->nullable()->comment('Request URL');
            
            // Additional Context
            $table->text('description')->nullable()->comment('Human-readable description');
            $table->json('metadata')->nullable()->comment('Additional context data');
            $table->string('event_type', 100)->nullable()->comment('Event classification');
            
            // Security & Compliance
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low')
                ->comment('Severity/importance of this action');
            $table->boolean('requires_review')->default(false)->comment('Flagged for review?');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignUlid('reviewed_by')->nullable()
                ->constrained('staff')->cascadeOnUpdate()->restrictOnDelete();
            
            // Timestamps (created_at only, no updates)
            $table->timestamp('created_at')->useCurrent();

            // Indexes for performance
            $table->index(['table_name', 'record_id']);
            $table->index('performed_by');
            $table->index('performed_at');
            $table->index('action');
            $table->index(['table_name', 'action']);
            $table->index('severity');
            $table->index('requires_review');
            $table->index('event_type');
            
            // Composite index for common queries
            $table->index(['table_name', 'record_id', 'performed_at'], 'idx_audit_record_timeline');
            $table->index(['performed_by', 'performed_at'], 'idx_audit_user_timeline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_audit_log');
    }
};

