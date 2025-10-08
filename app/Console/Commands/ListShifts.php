<?php

namespace App\Console\Commands;

use App\Models\StaffShift;
use Illuminate\Console\Command;

class ListShifts extends Command
{
    protected $signature = 'shifts:list {--all : Show all shifts including inactive}';
    protected $description = 'List all staff shifts in the database';

    public function handle()
    {
        $this->info('Staff Shifts in Database:');
        $this->line(str_repeat('=', 80));
        
        $query = StaffShift::query();
        
        if (!$this->option('all')) {
            $query->where('is_active', true);
        }
        
        $shifts = $query->orderBy('department')->orderBy('name')->get();
        
        if ($shifts->isEmpty()) {
            $this->warn('No shifts found in the database.');
            return 0;
        }
        
        $headers = ['Name', 'Department', 'Start Time', 'End Time', 'Active', 'Template'];
        $rows = [];
        
        foreach ($shifts as $shift) {
            $rows[] = [
                $shift->name,
                $shift->department ?? 'N/A',
                $shift->start_time ?? 'N/A',
                $shift->end_time ?? 'N/A',
                $shift->is_active ? 'Yes' : 'No',
                $shift->is_template ? 'Yes' : 'No',
            ];
        }
        
        $this->table($headers, $rows);
        
        $this->info('Total shifts: ' . $shifts->count());
        
        // Show filtering criteria for assignments page
        $this->line('');
        $this->info('Filters used on assignments page:');
        $templateShifts = $shifts->where('is_template', true)->where('is_active', true);
        $this->line('- Must be active (is_active = true)');
        $this->line('- Must be template (is_template = true)');
        $this->line('Template shifts shown on assignments page: ' . $templateShifts->count());
        
        if ($templateShifts->count() !== $shifts->where('is_active', true)->count()) {
            $this->warn('Some active shifts are not templates and won\'t show on assignments page!');
            $nonTemplateShifts = $shifts->where('is_active', true)->where('is_template', false);
            foreach ($nonTemplateShifts as $shift) {
                $this->line('  - ' . $shift->name . ' (not a template)');
            }
        }
        
        return 0;
    }
}