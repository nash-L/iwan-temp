<?php
namespace Sys;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Sys\Mvc\Request;
use function FastRoute\simpleDispatcher;
use Sys\Mvc\Response;
use Auryn\InjectionException;

class Router
{
    /**
     * @var string
     */
    private $controllerName;

    /**
     * @var string
     */
    private $methodName;

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
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     * @param string $controllerName
     */
    public function setControllerName(string $controllerName): void
    {
        $this->controllerName = $controllerName;
    }

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
     * @throws InjectionException
     */
    final public static function route(Request $request, Response $response)
    {
        $method = $request->server->get('REQUEST_METHOD');
        if ($method === 'OPTIONS') {
            return [function () use ($response) {
                $response->setStatusCode(200);
            }];
        }
        $dispatcher = simpleDispatcher(function (RouteCollector $r) use ($request) {
            $request->router = new static($r);
            $request->router->define($request);
        });
        $pathInfo = explode('?', $request->server->get('REQUEST_URI'))[0];
        list($uri, $suffix) = explode('.', $pathInfo . '.html');
        $response->setFormat($suffix);
        $routeInfo = $dispatcher->dispatch(
            $method,
            $uri = rawurldecode($uri)
        );
        return self::getRouteResult($routeInfo, $request, $response, $uri);
    }

    /**
     * @param array $routeInfo
     * @param Request $request
     * @param Response $response
     * @param string $uri
     * @return array
     * @throws InjectionException
     */
    final private static function getRouteResult(array $routeInfo, Request $request, Response $response, string $uri)
    {
        switch (array_shift($routeInfo)) {
            case Dispatcher::NOT_FOUND:
                if (empty($routeInfo = self::decodeUri($uri))) {
                    break;
                }
            case Dispatcher::FOUND:
                if (is_string($routeInfo[0][0]) && class_exists($routeInfo[0][0])) {
                    $request->router->setControllerName($routeInfo[0][0]);
                    $request->router->setMethodName($routeInfo[0][1]);
                    $file = $routeInfo[0][0] . '/' . $routeInfo[0][1];
                    $routeInfo[0][0] = Application::instance()->make($routeInfo[0][0]);
                    $namespace = Application::instance()->make(Config::class)->get('controller_namespace');
                    $response->setTemplate(preg_replace('/^' . strtr($namespace, ['\\' => '\\\\']) . '/', '', $file));
                }
                return $routeInfo;
            case Dispatcher::METHOD_NOT_ALLOWED:
                return [function ($method) use ($request, $response) {
                    $response->assign('method', $request->server->get('REQUEST_METHOD'));
                    $response->assign('allowed_method', $method);
                    $response->setStatusCode(405);
                }, ['method' => $routeInfo[0][0]]];
        }
        return [function () use ($response) {
            $response->setStatusCode(404);
        }];
    }

    final private static function decodeUri(string $uri)
    {
        $uriArr = array_filter(explode('/', $uri));
        $namespace = Application::instance()->make(Config::class)->get('controller_namespace');
        $default_controller_name = Application::instance()->make(Config::class)->get('default_controller_name');
        $default_action_name = Application::instance()->make(Config::class)->get('default_action_name');
        if (empty($uriArr)) {
            return [[$namespace . $default_controller_name, $default_action_name], []];
        }
        $result = [[], []];
        while ($uriItem = array_shift($uriArr)) {
            $valArr = array_map(function ($item) { return ucfirst($item); }, explode('-', $uriItem));
            $uriItem = implode('', $valArr);
            $namespace .= $uriItem;
            if (class_exists($namespace)) {
                $result[0][0] = $namespace;
            } else {
                $namespace .= '\\';
                continue;
            }
            if (empty($result[0][1] = array_shift($uriArr))) {
                $result[0][1] = $default_action_name;
            }
            $actionArr = array_map(function ($item) { return ucfirst($item); }, explode('-', $result[0][1]));
            $result[0][1] = lcfirst(implode('', $actionArr));
            if (!method_exists(...$result[0])) {
                return [];
            }
            while ($k = array_shift($uriArr)) {
                $result[1][$k] = array_shift($uriArr) ?? '';
            }
            return $result;
        }
        return [];
    }
}
