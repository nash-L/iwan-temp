<?php
namespace Sys;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Sys\Mvc\Request;
use function FastRoute\simpleDispatcher;
use Sys\Mvc\Response;

class Router
{
    /**
     * @var RouteCollector
     */
    protected $routeCollector;

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function define(Request $request)
    {}

    /**
     * Router constructor.
     * @param RouteCollector $r
     */
    final public function __construct(RouteCollector &$r)
    {
        $this->routeCollector = $r;
    }

    /**
     * Adds a route to the collection.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string|string[] $httpMethod
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function addRoute($httpMethod, $route, $handler)
    {
        $this->routeCollector->addRoute($httpMethod, $route, $handler);
        return $this;
    }

    /**
     * Create a route group with a common prefix.
     *
     * All routes created in the passed callback will have the given group prefix prepended.
     *
     * @param string $prefix
     * @param callable $callback
     * @return $this
     */
    protected function addGroup($prefix, callable $callback)
    {
        $this->routeCollector->addGroup($prefix, function () use ($callback) {
            return call_user_func($callback, $this);
        });
        return $this;
    }

    /**
     * Adds a GET route to the collection
     *
     * This is simply an alias of $this->addRoute('GET', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function get($route, $handler)
    {
        $this->addRoute('GET', $route, $handler);
        return $this;
    }

    /**
     * Adds a POST route to the collection
     *
     * This is simply an alias of $this->addRoute('POST', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function post($route, $handler)
    {
        $this->addRoute('POST', $route, $handler);
        return $this;
    }

    /**
     * Adds a PUT route to the collection
     *
     * This is simply an alias of $this->addRoute('PUT', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function put($route, $handler)
    {
        $this->addRoute('PUT', $route, $handler);
        return $this;
    }

    /**
     * Adds a DELETE route to the collection
     *
     * This is simply an alias of $this->addRoute('DELETE', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function delete($route, $handler)
    {
        $this->addRoute('DELETE', $route, $handler);
        return $this;
    }

    /**
     * Adds a PATCH route to the collection
     *
     * This is simply an alias of $this->addRoute('PATCH', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function patch($route, $handler)
    {
        $this->addRoute('PATCH', $route, $handler);
        return $this;
    }

    /**
     * Adds a HEAD route to the collection
     *
     * This is simply an alias of $this->addRoute('HEAD', $route, $handler)
     *
     * @param string $route
     * @param mixed  $handler
     * @return $this
     */
    protected function head($route, $handler)
    {
        $this->addRoute('HEAD', $route, $handler);
        return $this;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return array
     */
    final public static function route(Request $request, Response $response)
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($request) {
            $router = new static($r);
            $router->define($request);
        });
        $pathInfo = explode('?', $request->server->get('REQUEST_URI'))[0];
        list($uri, $suffix) = explode('.', $pathInfo . '.html');
        $response->setFormat($suffix);
        $routeInfo = $dispatcher->dispatch(
            $request->server->get('REQUEST_METHOD'),
            rawurldecode($uri)
        );
        return self::getRouteResult($routeInfo, $request, $response);
    }

    /**
     * @param array $routeInfo
     * @param Request $request
     * @param Response $response
     * @return array
     */
    final private static function getRouteResult(array $routeInfo, Request $request, Response $response)
    {
        switch (array_shift($routeInfo)) {
            case Dispatcher::FOUND: return $routeInfo;
            case Dispatcher::METHOD_NOT_ALLOWED: return [function ($method) use ($request, $response) {
                $response->assign('method', $request->server->get('REQUEST_METHOD'));
                $response->assign('allowed_method', $method);
                $response->setStatusCode(405);
            }, ['method' => $routeInfo[0][0]]];
        }
        return [function () use ($response) {
            $response->setStatusCode(400);
        }];
    }
}
