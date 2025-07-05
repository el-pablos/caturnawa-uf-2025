<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSqlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Starting SQL import from caturnawa_uf.sql...');
        
        // Path to SQL file
        $sqlFile = base_path('caturnawa_uf.sql');
        
        if (!File::exists($sqlFile)) {
            $this->command->error('❌ SQL file not found: ' . $sqlFile);
            return;
        }
        
        // Read SQL file
        $sql = File::get($sqlFile);
        
        // Remove database creation and use statements
        $sql = preg_replace('/CREATE DATABASE.*?;/i', '', $sql);
        $sql = preg_replace('/USE `.*?`;/i', '', $sql);
        
        // Split SQL into individual statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            function($statement) {
                return !empty($statement) && 
                       !preg_match('/^\/\*.*?\*\/$/s', $statement) &&
                       !preg_match('/^--/', $statement) &&
                       !preg_match('/^SET/', $statement) &&
                       !preg_match('/^\/\*!/', $statement);
            }
        );
        
        $this->command->info('📊 Found ' . count($statements) . ' SQL statements to execute');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($statements as $index => $statement) {
            try {
                // Skip empty statements
                if (empty(trim($statement))) {
                    continue;
                }
                
                // Execute statement
                DB::statement($statement);
                $successCount++;
                
                // Show progress every 10 statements
                if (($index + 1) % 10 === 0) {
                    $this->command->info("✅ Executed {$successCount} statements...");
                }
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->command->warn("⚠️  Error in statement " . ($index + 1) . ": " . $e->getMessage());
                
                // Show problematic statement (first 100 chars)
                $preview = substr(trim($statement), 0, 100) . '...';
                $this->command->warn("Statement: " . $preview);
                
                // Continue with next statement
                continue;
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('📈 Import Summary:');
        $this->command->info("✅ Successful: {$successCount} statements");
        $this->command->info("⚠️  Errors: {$errorCount} statements");
        
        if ($errorCount === 0) {
            $this->command->info('🎉 SQL import completed successfully!');
        } else {
            $this->command->warn('⚠️  SQL import completed with some errors (this is usually normal)');
        }
        
        // Show imported data summary
        $this->showDataSummary();
    }
    
    /**
     * Show summary of imported data
     */
    private function showDataSummary(): void
    {
        $this->command->info('📊 Imported Data Summary:');
        
        try {
            $tables = [
                'users' => 'Users',
                'competitions' => 'Competitions', 
                'registrations' => 'Registrations',
                'submissions' => 'Submissions',
                'payments' => 'Payments',
                'scores' => 'Scores',
                'roles' => 'Roles',
                'permissions' => 'Permissions',
                'settings' => 'Settings'
            ];
            
            foreach ($tables as $table => $label) {
                try {
                    $count = DB::table($table)->count();
                    $this->command->info("   {$label}: {$count} records");
                } catch (\Exception $e) {
                    $this->command->warn("   {$label}: Table not found or error");
                }
            }
            
        } catch (\Exception $e) {
            $this->command->warn('Could not generate data summary: ' . $e->getMessage());
        }
    }
}
