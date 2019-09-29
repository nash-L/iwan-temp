<?php
use App\Router;
return [
    'router_class' => Router::class,
    'html_engine' => [
        'template_class_prefix' => '_Tpl_',
        'cache' => ROOT . '/runtime/cache/views',
        'cache_file_mode' => 0666,
        'cache_lambda_templates' => true,
        'loader' => new Mustache_Loader_FilesystemLoader(ROOT . '/App/views/template', ['extension' => '.html']),
        'partials_loader' => new Mustache_Loader_FilesystemLoader(ROOT . '/App/views/partials', ['extension' => '.html']),
        'escape' => function ($val) {
            return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
        },
        'charset' => 'UTF-8',
        'strict_callables' => true,
        'pragmas' => [Mustache_Engine::PRAGMA_FILTERS]
    ]
];
