<?php
namespace Sys;

use Auryn\Injector;
use Auryn\ConfigException;
use Sys\Mvc\Request;
use Sys\Mvc\Response;
use Auryn\InjectionException;
use Mustache_Engine;

class Application extends Injector
{
    /**
     * Application constructor.
     * @param Request $request
     * @param Response $response
     * @throws ConfigException
     */
    public function __construct(Request $request, Response $response)
    {
        parent::__construct(null);
        $this->share($this);
        $this->share($request);
        $this->share($response);
    }

    /**
     * @param Config $config
     * @throws ConfigException
     * @throws InjectionException
     */
    public function dispatch(Config $config)
    {
        $this->share($config);
        $this->make(Response::class)
            ->setEngine(new Mustache_Engine($config->get('html_engine')));
        $this->executeRouter($config->get('router_class'));
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
        if ($result instanceof \Symfony\Component\HttpFoundation\Response) {
            $this->make(Response::class)
                ->setFormat('raw')
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
