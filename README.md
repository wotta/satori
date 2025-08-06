# Satori

<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://banners.beyondco.de/satori.png?theme=dark&packageManager=composer+require&packageName=wottavm%2Fsatori&pattern=leaf&style=style_2&description=An+opinionated+Laravel%2C+Filament%2C+Blueprint%2C+and+React+starter+kit.&md=1&showWatermark=0&fontSize=100px&images=sun">
    <source media="(prefers-color-scheme: light)" srcset="https://banners.beyondco.de/satori.png?theme=light&packageManager=composer+require&packageName=wottavm%2Fsatori&pattern=leaf&style=style_2&description=An+opinionated+Laravel%2C+Filament%2C+Blueprint%2C+and+React+starter+kit.&md=1&showWatermark=0&fontSize=100px&images=sun">
    <img alt="Fission Logo" src="https://banners.beyondco.de/satori.png?theme=light&packageManager=composer+require&packageName=wottavm%2Fsatori&pattern=leaf&style=style_2&description=An+opinionated+Laravel%2C+Filament%2C+Blueprint%2C+and+React+starter+kit.&md=1&showWatermark=0&fontSize=100px&images=sun">
  </picture>
</p>

> [!IMPORTANT]
> This starter kit is created by Wouter van Marrum using Laravel's react starterkit, [Blueprint](https://blueprint.laravelshift.com), and [filament](https://filamentphp.com). While PRs are welcome, this is made to fit my personal needs to be able to create MVP's fast without AI.
> When Laravel Boost gets released I can only imagine how much more efficient I can be with it when combined in this starter template.

> [!TIP]
> To get up and running quickly, use the new Laravel installer with the using option: `laravel new my-project --using=wottavm/satori`

## Installation guide

This project includes a custom installation script that streamlines the setup process. If you are not using the Laravel installer, you can still use this script to install Satori. Use the composer create command to do so: `composer create-project wottavm/satori myapp`.

- If you don't have a `.env` yet, copy the `.env.example` to `.env`.
- Run `composer install`.
- Run `npm install`.
- Run `php artisan satori:install`. This will prompt to create a new filament panel, initialize blueprint, and install prism or the openai package.
- Provides instructions to start local development server

## Additional information

If you use Laravel Sail for your dev server locally I've included a custom `supervisord.conf` that automatically runs horizon on in the background.

## License

The Satori starter kit is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### Thanks

This starterkit is inspired by [Josh Cirre's](https://x.com/joshcirre?utm_source=https://github.com/wotta/satori) [fission](https://github.com/joshcirre/fission) a Laravel Folio, and Livewire Volt starterkit.
