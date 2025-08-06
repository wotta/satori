# Satori

> [!IMPORTANT]
> This starter kit is created by Wouter van Marrum using Laravel's react starterkit, blueprint, and filament. While PRs are welcome, this is made to fit my personal needs to be able to create MVP's fast without AI.
> When Laravel Boost gets released I can only imagine how much more efficient I can be with it when combined in this starter template.

> [!TIP]
> To get up and running quickly, use the new Laravel installer with the using option: `laravel new my-project --using=wotta/satori`

## Installation guide

This project includes a custom installation script that streamlines the setup process. If you are not using the Laravel installer, you can still use this script to install Satori. Use the composer create command to do so: `composer create-project wotta/satori myapp`.

- If you don't have a `.env` yet, copy the `.env.example` to `.env`.
- Run `composer install`.
- Run `npm install`.
- Run `php artisan satori:install`. This will prompt to create a new filament panel, initialize blueprint, and install prism or the openai package.
- Provides instructions to start local development server

## License

The Satori starter kit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### Thanks

This starterkit is inspired by [Josh Cirre's](https://x.com/joshcirre?utm_source=https://github.com/wotta/satori) [fission](https://github.com/joshcirre/fission) a Laravel Folio, and Livewire Volt starterkit.
