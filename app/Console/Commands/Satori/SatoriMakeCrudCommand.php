<?php

declare(strict_types=1);

namespace App\Console\Commands\Satori;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as BaseCommand;

use function Filament\Support\discover_app_classes;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\suggest;

final class SatoriMakeCrudCommand extends Command
{
    protected $signature = 'satori:make:crud';

    protected $description = 'Command description';

    public function handle(): int
    {
        if (! File::exists(base_path('draft.yml'))) {
            $this->callSilent('blueprint:init');
        }

        if (! confirm('Do you want to run blueprint:build?')) {
            $this->comment('Stopping script here.');

            return BaseCommand::SUCCESS;
        }

        $output = $this->callSilent('blueprint:build');
        if ($output === BaseCommand::FAILURE) {
            $this->error('Blueprint build failed');

            return BaseCommand::FAILURE;
        }

        $this->info('Blueprint built successfully');

        if (! confirm('Do you want to migrate the database?')) {
            $this->comment('Stopping script here.');

            return BaseCommand::SUCCESS;
        }

        $output = $this->callSilent('migrate', [
            '--force' => true,
            '--no-interaction' => true,
        ]);

        if ($output === BaseCommand::FAILURE) {
            $this->error('Migration failed');

            return BaseCommand::FAILURE;
        }

        $this->newLine(2);
        $this->info('Creating filament crud');

        $modelFqns = discover_app_classes(parentClass: Model::class);

        $model = suggest(
            label: 'What is the model?',
            options: function (string $search) use ($modelFqns): array {
                $search = str($search)->trim()->replace(['\\', '/'], '');

                if (blank($search)) {
                    return $modelFqns;
                }

                return array_filter(
                    $modelFqns,
                    fn (string $class): bool => str($class)->replace(['\\', '/'], '')->contains($search, ignoreCase: true),
                );
            },
            placeholder: 'App\\Models\\BlogPost',
            required: true,
        );

        $this->callSilent('make:filament-resource', [
            'model' => class_basename($model),
            '-n',
        ]);

        return BaseCommand::SUCCESS;
    }
}
