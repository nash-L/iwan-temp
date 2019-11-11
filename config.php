<?php
use App\Router;
use Sys\Migration\AbstractMigration;

defined('ROOT') || define('ROOT', realpath(__DIR__));

return [
    'router_class' => Router::class,
//    'console_class' => Router::class,
    'controller_namespace' => 'App\\Controllers\\',
    'command_namespace' => 'App\\Commands\\',
    'default_controller_name' => 'Home',
    'default_action_name' => 'index',
    'runtime_dir' => ROOT . '/runtime',
    'catch_dir' => ROOT . '/runtime/cache',
    'cors' => [
        'origin' => '*',
        'methods' => 'PATCH,GET,POST,PUT,DELETE',
        'headers' => 'Authorization'
    ],
    /**
     * Phinx
     * http://docs.phinx.org/en/latest/install.html
     */
    'migrate' => [
        'paths' => [
            'migrations' => ROOT . '/db/migrations',
            'seeds' => ROOT . '/db/seeds',
        ],
        'migration_base_class' => AbstractMigration::class,
        'default_migration_table' => 'migration',
        'version_order' => 'creation'
    ],
    /**
     * Medoo
     * https://medoo.in/api/where
     */
    'database' => [
        'default_database' => 'development',
        'production' => [
            // 必须参数
            'database_type' => 'mysql',
            'server' => 'localhost',
            'database_name' => 'production_db',
            'username' => 'root',
            'password' => 'root',
            // 非必须参数
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            # 'prefix' => 'production_'
        ],
        'docker' => [
            // 必须参数
            'database_type' => 'mysql',
            'server' => 'mysql',
            'database_name' => 'dc_development',
            'username' => 'root',
            'password' => 'root',
            // 非必须参数
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            # 'prefix' => 'development_'
        ],
        'development' => [
            // 必须参数
            'database_type' => 'mysql',
            'server' => '127.0.0.1',
            'database_name' => 'dc_development',
            'username' => 'root',
            'password' => 'root',
            // 非必须参数
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            # 'prefix' => 'development_'
        ],
        'testing' => [
            // 必须参数
            'database_type' => 'mysql',
            'server' => 'localhost',
            'database_name' => 'testing_db',
            'username' => 'root',
            'password' => 'root',
            // 非必须参数
            'port' => 3306,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            # 'prefix' => 'testing_'
        ]
    ],
    /**
     * mustache.php
     * https://github.com/bobthecow/mustache.php/wiki
     */
    'html_engine' => [
        'delimiters' => '<!--{ }-->',
        'template_class_prefix' => '_Tpl_',
        'cache' => ROOT . '/runtime/cache/views',
        'cache_file_mode' => 0666,
        'cache_lambda_templates' => true,
        'loader' => new Mustache_Loader_FilesystemLoader(ROOT . '/App/views', ['extension' => '.html']),
        'partials_loader' => new Mustache_Loader_FilesystemLoader(ROOT . '/App/views', ['extension' => '.html']),
        'escape' => function ($val) {
            return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
        },
        'charset' => 'UTF-8',
        'strict_callables' => true,
        'pragmas' => [Mustache_Engine::PRAGMA_FILTERS, Mustache_Engine::PRAGMA_BLOCKS]
    ]
];
