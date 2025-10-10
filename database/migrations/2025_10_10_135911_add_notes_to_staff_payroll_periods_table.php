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
        Schema::table('staff_payroll_periods', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('payroll_setting_id')
                ->comment('Additional notes for this payroll period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_payroll_periods', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
