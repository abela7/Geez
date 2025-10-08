<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTableStructure extends Command
{
    protected $signature = 'table:check {table}';
    protected $description = 'Check table structure';

    public function handle()
    {
        $table = $this->argument('table');
        
        $this->info("Table structure for: {$table}");
        $this->line(str_repeat('=', 50));
        
        $columns = DB::select("DESCRIBE {$table}");
        
        foreach ($columns as $column) {
            $this->line("{$column->Field} - {$column->Type}");
        }
        
        return 0;
    }
}