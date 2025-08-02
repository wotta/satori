<?php

declare(strict_types=1);

namespace App\Console\Commands\Satori;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as BaseCommand;

use function Laravel\Prompts\confirm;

final class SatoriMakeCrudCommand extends Command
{
    protected $signature = 'satori:make:crud';

    protected $description = 'Command description';

    public function handle(): int
    {
        if (! File::exists(base_path('draft.yml'))) {
            Artisan::call('blueprint:init');
        }

        if (! confirm('Do you want to run blueprint:build?')) {
            $this->comment('Stopping script here.');

            return BaseCommand::SUCCESS;
        }

        $output = Artisan::call('blueprint:build');
        if ($output === BaseCommand::FAILURE) {
            $this->error('Blueprint build failed');

            return BaseCommand::FAILURE;
        }

        $this->info('Blueprint built successfully');

        if (! confirm('Do you want to migrate the database?')) {
            $this->comment('Stopping script here.');

            return BaseCommand::SUCCESS;
        }

        $output = Artisan::call('migrate', [
            '--force' => true,
            '--no-interaction' => true,
        ]);

        if ($output === BaseCommand::FAILURE) {
            $this->error('Migration failed');

            return BaseCommand::FAILURE;
        }

        $this->newLine(2);
        $this->info('Creating filament crud');

        return BaseCommand::SUCCESS;
    }
}
