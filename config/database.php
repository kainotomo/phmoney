<?php

use Illuminate\Support\Str;

return [

    'phmoney_portfolio' => [
           'driver' => 'mysql',
           'url' => null,
           'host' => 'mariadb',
           'port' => '3306',
           'database' => 'phmoney_dev',
           'username' => 'root',
           'password' => 'root',
           'unix_socket' => '',
           'charset' => 'utf8mb4',
           'collation' => 'utf8mb4_unicode_ci',
           'prefix' => 'phmprt_',
           'prefix_indexes' => 1,
           'strict' => 0,
           'engine' => null,
           'options' => [],
       ],

       'phmoney_sqlite' => [
        'driver' => 'sqlite',
        'url' => env('DATABASE_URL_PORTFOLIO'),
        'database' => env('DB_DATABASE', storage_path('app/import/sqlite/')),
        'prefix' => '',
        'foreign_key_constraints' => env('DB_FOREIGN_KEYS_PORTFOLIO', true),
    ],

];
