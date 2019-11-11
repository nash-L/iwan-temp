<?php
namespace Sys;

use Auryn\Injector;
use Auryn\ConfigException;
use Sys\Mvc\Request;
use Sys\Mvc\Response;
use Auryn\InjectionException;
use Mustache_Engine;
use Exception;

class Application extends Injector
{
    private static $application = null;

    public static function instance()
    {
        return self::$application;
    }

    /**
     * Application constructor.
     * @throws ConfigException
     */
    public function __construct()
    {
        parent::__construct(null);
        self::$application = $this;
        $this->share($this);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Config $config
     * @throws ConfigException
     * @throws InjectionException
     */
    public function dispatch(Request $request, Response $response, Config $config)
    {
        $this->share($config);
        $this->share($request);
        $this->share($response);
        $this->make(Response::class)
            ->setEngine(new Mustache_Engine($config->get('html_engine')));
        $this->executeRouter($config->get('router_class'));
    }

    /**
     * @param Config $config
     * @throws Exception
     */
    public function console(Config $config)
    {
        $this->share($config);
        $this->executeConsole($config->get('console_class'));
    }

    /**
     * @param string|null $console_class
     * @throws InjectionException
     */
    protected function executeConsole(?string $console_class)
    {
        if (!$console_class || !class_exists($console_class) || !is_subclass_of($console_class, Console::class)) {
            $console_class = Console::class;
        }
        $this->execute([$console_class, 'run']);
    }

    /**
     * @param string|null $router_class
     * @throws InjectionException
     */
    protected function executeRouter(?string $router_class)
    {
        if (!$router_class || !class_exists($router_class) || !is_subclass_of($router_class, Router::class)) {
            $router_class = Router::class;
        }
        $routeResult = $this->execute([$router_class, 'route']);
        $result = $this->execute(...$routeResult);
        $response = $this->make(Response::class);
        if ($result instanceof \Symfony\Component\HttpFoundation\Response && $result !== $response) {
            $response->setFormat('raw')
                ->setContent($result->getContent())
                ->setHeaders($result->headers->all());
        }
    }

    /**
     * @param array $args
     * @return array
     */
    protected function makeParams(array $args)
    {
        $params = [];
        foreach ($args as $k => $v) {
            if ($v[0] !== ':') {
                $k = ':' . $k;
            }
            $params[$k] = $v;
        }
        return $params;
    }

    public function define($name, array $args)
    {
        return parent::define($name, $this->makeParams($args));
    }

    public function make($name, array $args = [])
    {
        return parent::make($name, $this->makeParams($args));
    }

    public function execute($callableOrMethodStr, array $args = [])
    {
        return parent::execute($callableOrMethodStr, $this->makeParams($args));
    }

    public function share($nameOrInstance, array $args = [])
    {
        if (is_string($nameOrInstance) && $args && class_exists($nameOrInstance)) {
            $this->define($nameOrInstance, $args);
        }
        return parent::share($nameOrInstance);
    }
}
