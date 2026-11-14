<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use App\Models\PotensiInvestasi;
use App\Models\MinatInvestor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gis:backup {--path= : Custom backup path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup GIS database data to JSON files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $backupPath = $this->option('path') ?: "backups/{$timestamp}";

        $this->info("Starting database backup to: {$backupPath}");

        // Create backup directory
        Storage::makeDirectory($backupPath);

        // Backup kelurahans
        $this->backupTable(Kelurahan::class, 'kelurahans', $backupPath);

        // Backup potensi investasis
        $this->backupTable(PotensiInvestasi::class, 'potensi_investasis', $backupPath);

        // Backup minat investors
        $this->backupTable(MinatInvestor::class, 'minat_investors', $backupPath);

        $this->info('Database backup completed successfully!');
        $this->info("Backup location: storage/app/{$backupPath}");
    }

    private function backupTable($model, $tableName, $backupPath)
    {
        $this->info("Backing up {$tableName}...");

        $data = $model::all()->toJson(JSON_PRETTY_PRINT);

        Storage::put("{$backupPath}/{$tableName}.json", $data);

        $count = $model::count();
        $this->info("✓ {$tableName}: {$count} records backed up");
    }
}
