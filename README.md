<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Laravel 9 API - JWT Cookie - Token CSRF - Pest PHP

### Install dependencies

```bash
composer install
```

### Update dependencies

```bash
composer update
```

### Start dev server

```bash
php artisan serve
```

### Start Pest testing

```bash
./vendor/bin/pest
```
```bash
./vendor/bin/pest --coverage
```

## Contributing

If you want to contribute to the project, please send PR

### Divers

Don't forget to create your .env from .env.example and add your JWT_SECRET in your .env with `php artisan jwt:secret` and paste a different one in .env.testing. If you add JWT_SECRET in your env.testing, you have to add this file to your .gitignore.
