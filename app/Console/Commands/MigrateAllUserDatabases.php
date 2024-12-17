<?php

namespace App\Console\Commands;

use Package\XAuth\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class MigrateAllUserDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:all-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description ='Run migrations on all user databases';
    /**
     * Execute the console command.
     */



    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->info("Migrating database for user ID: {$user->id}");
            setDatabaseForUser($user->id);

            Artisan::call('migrate', [
                '--database' => 'sqlite_user',
                '--force' => true,
            ]);

            $this->info("Migration completed for user ID: {$user->id}");
        }

        $this->info("Migrations completed for all users.");
    }
}
