<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CleanupDatabaseUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $folderPath = base_path('database/users');

        DB::disconnect();

        if (is_dir($folderPath)) {
            foreach (glob($folderPath . '/*') as $file) {
                try {
                    if (is_file($file)) {
                        unlink($file);
                    } else {
                        $this->deleteFolderRecursively($file);
                    }
                } catch (\Exception $e) {
                    echo "Could not delete file {$file}: {$e->getMessage()}\n";
                }
            }
        }
    }

    protected function deleteFolderRecursively($folder)
    {
        foreach (glob($folder . '/*') as $file) {
            is_dir($file) ? $this->deleteFolderRecursively($file) : unlink($file);
        }
        rmdir($folder);
    }
}
