<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as BaseCommand;

final class SatoriInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'satori:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the Satori installation setup.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (app()->isLocal() === false) {
            $this->error('This command can only be run in a local environment.');

            return BaseCommand::FAILURE;
        }

        $this->info('Satori is being installed.');
        $this->newLine(2);

        $this->warn('woot');

        return BaseCommand::SUCCESS;
    }
}
