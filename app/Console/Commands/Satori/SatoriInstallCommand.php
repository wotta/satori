<?php

declare(strict_types=1);

namespace App\Console\Commands\Satori;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command as BaseCommand;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

#[AsCommand('satori:install', description: 'Runs the Satori installation setup.')]
final class SatoriInstallCommand extends Command
{
    /** @var string[] self::PACKAGES */
    private const array PACKAGES = [
        'prism-php/prism' => '^0.82',
        'vizra/vizra-adk' => '^0.0',
        'openai-php/client' => '^0.8',
    ];

    /**
     * Execute the console command.
     *
     * @throws JsonException
     */
    public function handle(): int
    {
        if (app()->isLocal() === false) {
            $this->error('This command can only be run in a local environment.');

            return BaseCommand::FAILURE;
        }

        $this->info('Satori is being installed.');
        $this->newLine(2);

        $runMigration = confirm('Do you want to run migrations?');

        if (confirm('Are you planning to integrate AI within your application?')) {
            $this->installAiPackage();
        }

        if (
            (config('database.default') === 'sqlite') &&
            (! File::exists(database_path('database.sqlite')))
        ) {
            touch(database_path('database.sqlite'));
        }

        $panelName = text(
            label: 'What is the panel ID? (e.g. admin)',
            default: 'admin',
            hint: 'This will be displayed on your profile.'
        );

        $this->call('filament:make-panel', [
            'id' => $panelName,
            '-n' => true,
        ]);
        $this->comment('Filament panel is initialized.');
        $this->line('');

        $this->call('blueprint:init', [
            '-n' => true,
        ]);
        $this->comment('Blueprint is initialized.');
        $this->line('');

        if (config('app.key') === null) {
            $this->info('App key was not set yet. running key generate.');
            $this->callSilent('key:generate', ['--force' => true, '-n' => true]);
            $this->comment('App key is generated.');
            $this->line('');
        }

        $this->restorePrompts();

        if ($runMigration) {
            $this->comment('Migration is successful.');
            $this->line('');
        }

        $this->call('boost:install');

        $this->info('To run the local development machine use one of the following commands:');
        $this->line('');
        $this->comment('1. composer run dev');
        $this->line('');
        $this->comment('2. ./vendor/bin/sail up -d');

        return BaseCommand::SUCCESS;
    }

    /**
     * @throws FileNotFoundException
     * @throws JsonException
     */
    private function installAiPackage(): void
    {
        $composerJson = File::json(base_path('composer.json'));

        $packageToInstall = suggest(
            label: 'Which package do you want to install?',
            options: fn (string $value) => collect(array_keys(self::PACKAGES))
                ->filter(fn (string $name) => Str::contains($name, $value, ignoreCase: true))
        );

        if (! isset($composerJson['require'][$packageToInstall])) {
            $composerJson['require'][$packageToInstall] = self::PACKAGES[$packageToInstall];
        }

        File::put(base_path('composer.json'), json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));

        $this->info('Running composer update command to install package');
        exec('composer update '.$packageToInstall.' --no-interaction');
        $this->newLine(2);

        switch ($packageToInstall) {
            case 'prism-php/prism':
                $this->call('vendor:publish', [
                    '--tag' => 'prism-config',
                ]);

                if (! File::exists(config_path('prism.php'))) {
                    $this->error('Could not publish prism config.');
                    $this->newLine(2);
                    $this->info('Check if prism was installed correctly. If not run the following commands:');
                    $this->line(str_repeat('<fg=white;bg=gray> </>', 60));
                    $this->line('<fg=white;bg=gray>    $ composer require '.$packageToInstall.str_repeat(' ', 22).'</>');
                    $this->line('<fg=white;bg=gray>    $ php artisan vendor:publish --tag prism-config'.str_repeat(' ', 9).'</>');
                    $this->line(str_repeat('<fg=white;bg=gray> </>', 60));
                    $this->newLine(2);
                }

                return;
            case 'vizra/vizra-adk':
                $this->call('vizra:install');

                $this->newLine(2);
                $this->info('Create your first agent check out the documentation: https://github.com/vizra-ai/vizra-adk?tab=readme-ov-file#-quick-start');

                return;
            case 'openai-php/client':
                break;
        }

        $this->line('Package installed');
    }
}
