<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BackupCommandTest extends TestCase
{
    protected string $backupDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupDir = storage_path('app/backups');
        if (!File::isDirectory($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    public function test_backup_command_creates_valid_archive(): void
    {
        $exitCode = \Illuminate\Support\Facades\Artisan::call('app:backup', ['--keep' => 3]);
        $this->assertSame(0, $exitCode);

        clearstatcache();
        $this->assertTrue(File::isDirectory($this->backupDir), 'Backup directory was not created');

        $files = collect(File::files($this->backupDir))
            ->filter(fn ($f) => str_starts_with($f->getFilename(), 'omnitools_backup_') && str_ends_with($f->getFilename(), '.zip'))
            ->sortByDesc(fn ($f) => $f->getMTime());

        $this->assertNotEmpty($files, 'No backup zip file was produced');

        $latestBackup = $files->first()->getRealPath();
        $this->assertFileExists($latestBackup);
        $this->assertGreaterThan(50, filesize($latestBackup), 'Backup archive appears corrupted or empty');

        // Verify it is a valid zip archive
        $zip = new \ZipArchive();
        $res = $zip->open($latestBackup);
        $this->assertTrue($res === true, 'Backup file is not a valid zip archive');

        $hasDbEntry = false;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (str_contains($filename, 'database/') || str_contains($filename, 'database_dump_')) {
                $hasDbEntry = true;
                break;
            }
        }
        $zip->close();

        $this->assertTrue($hasDbEntry, 'Backup archive does not contain a database dump');
    }

    public function test_backup_command_prunes_older_archives(): void
    {
        // Clean out any existing test backups first
        foreach (File::files($this->backupDir) as $f) {
            if (str_ends_with($f->getFilename(), '.zip')) {
                @unlink($f->getRealPath());
            }
        }

        // Create 3 dummy older archives
        for ($i = 1; $i <= 3; $i++) {
            $dummyPath = $this->backupDir . DIRECTORY_SEPARATOR . "omnitools_backup_2020_0{$i}.zip";
            file_put_contents($dummyPath, 'PK dummy zip content');
            touch($dummyPath, time() - (86400 * (10 - $i)));
        }

        // Run with keep=2 synchronously
        $exitCode = \Illuminate\Support\Facades\Artisan::call('app:backup', ['--keep' => 2]);
        $this->assertSame(0, $exitCode);

        clearstatcache();
        $files = collect(File::files($this->backupDir))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.zip'));

        $this->assertLessThanOrEqual(2, $files->count(), 'Backup pruning failed to enforce maximum archive retention');

        // Clean up remaining test archives
        foreach (File::files($this->backupDir) as $f) {
            if (str_ends_with($f->getFilename(), '.zip')) {
                @unlink($f->getRealPath());
            }
        }
    }
}
