# Laravel Utilities Package

## Purpose

A tool box of Laravel specific utilties.

## Tools

Alert - An easy way to display and work with Laravel flash data in your HTML.

## Install via Composer

Add the following to your "require" schema:

```
"require": {
     "priskz/laravel-utilities": "~0.0.1"
}
```

Run ```composer install```

Add ```'LaravelUtilities\Alert\ServiceProvider'``` to the ```'providers'``` in ```/app/laravel/config/app.php``` to enable the newly added service.