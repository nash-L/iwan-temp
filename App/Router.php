<?php


namespace App;


use App\Controllers\Home;
use Sys\Mvc\Request;
use Sys\Mvc\Response;

class Router extends \Sys\Router
{
    public function define(Request $request)
    {
        $this->addGroup('/service', function () {
            $this->get('/test', [Home::class, 'test']);
        });
    }
}