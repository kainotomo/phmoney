<?php

use Illuminate\Support\Str;

return [

    'phmoney_portfolio' => [],

       'phmoney_sqlite' => [
        'driver' => 'sqlite',
        'url' => env('DATABASE_URL_PORTFOLIO'),
        'database' => env('DB_DATABASE', storage_path('app/import/sqlite/')),
        'prefix' => '',
        'foreign_key_constraints' => env('DB_FOREIGN_KEYS_PORTFOLIO', true),
    ],

];
