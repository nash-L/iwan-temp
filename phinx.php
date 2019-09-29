<?php

use Sys\Migration\AbstractMigration;

return [
    'paths' => [
        'migrations' => __DIR__ . '/db/migrations',
        'seeds' => __DIR__ . '/db/seeds',
    ],
    'migration_base_class' => AbstractMigration::class,
    'environments' => [
        'default_migration_table' => 'migration',
        'default_database' => 'development',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => 'pass',
            'port' => 3306,
            'charset' => 'utf8',
        ],
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'pay_center',
            'user' => 'root',
            'pass' => 'root',
            'port' => 3306,
            'charset' => 'utf8',
        ],
        'testing' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => 'pass',
            'port' => 3306,
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];
