<?php
use App\Router;
return [
    'router_class' => Router::class,
    'controller_namespace' => 'App\\Controllers\\',
    'default_controller_name' => 'Home',
    'default_action_name' => 'index',
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
