<?php

use Illuminate\Support\Str;

return [

    'mysql_portfolio' => [
        'driver' => 'mysql',
        'url' => env('DATABASE_URL_PORTFOLIO'),
        'host' => env('DB_HOST_PORTFOLIO', '127.0.0.1'),
        'port' => env('DB_PORT_PORTFOLIO', '3306'),
        'database' => env('DB_DATABASE_PORTFOLIO', 'forge'),
        'username' => env('DB_USERNAME_PORTFOLIO', 'forge'),
        'password' => env('DB_PASSWORD_PORTFOLIO', ''),
        'unix_socket' => env('DB_SOCKET_PORTFOLIO', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => env('DB_PREFIX_PORTFOLIO', ''),
        'prefix_indexes' => true,
        'strict' => false,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],

];
