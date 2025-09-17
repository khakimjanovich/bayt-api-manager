<?php

declare(strict_types=1);

namespace Khakimjanovich\BaytApiManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand('bayt-api-manager:migrate', 'Migrate bayt api migrations to database')]
final class MigrateCommand extends Command
{
    public function handle(): int
    {

        $this->info('Migrating mbc migrations...');

        $migrationPath = 'vendor/khakimjanovich/bayt-api-manager/database/migrations'; // Update path as needed

        if (! File::isDirectory($migrationPath)) {
            $this->error("Migration path not found: $migrationPath");

            return 1;
        }

        $migrationFiles = collect(File::files($migrationPath))
            ->map(fn ($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME));

        DB::table('migrations')->whereIn('migration', $migrationFiles)->delete();

        $this->info('Deleted migration records from database.');

        $tables_to_drop = [
            'bayt_api_manager_provinces',
            'bayt_api_manager_districts',
            'bayt_api_manager_mosques',
        ];

        $dropped_tables = '';
        foreach ($tables_to_drop as $table) {
            if (Schema::hasTable($table)) {
                Schema::drop($table);
                $dropped_tables .= $table.', ';
            }
        }
        if ($dropped_tables) {
            $this->info("Dropped tables: $dropped_tables");
        }

        $this->call('migrate', [
            '--path' => $migrationPath,
        ]);

        return self::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
        ];
    }
}
