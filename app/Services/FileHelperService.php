<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

final class FileHelperService
{
    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    public function getModels(): array
    {
        $modelPath = is_dir(app_path('Models')) ? app_path('Models') : app_path();

        return new Collection(Finder::create()->files()->depth(0)->in($modelPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }
}
