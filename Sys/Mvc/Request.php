<?php


namespace Sys\Mvc;


use Sys\Router;

class Request extends \Symfony\Component\HttpFoundation\Request
{
    /**
     * @var Router
     */
    public $router;
}