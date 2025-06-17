<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    */
    'providers' => ServiceProvider::defaultProviders()->merge([

        /*
         * Package Service Providers...
         */
        // Milon\Barcode\BarcodeServiceProvider::class, ❌ HAPUS jika tidak dipakai
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class, // ✅ Tambahkan untuk QRCode

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */
    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,

        // 'DNS1D' => Milon\Barcode\Facades\DNS1DFacade::class, ❌ HAPUS
        // 'DNS2D' => Milon\Barcode\Facades\DNS2DFacade::class, ❌ HAPUS

        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class, // ✅ Tambahkan alias QRCode
    ])->toArray(),

];
