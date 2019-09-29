<?php


namespace App;


use Sys\Mvc\Request;
use Sys\Mvc\Response;

class Router extends \Sys\Router
{
    public function define(Request $request)
    {
        $this->addGroup('/service', function () {
            $this->post('/test', function (Response $response) {
                $response->assign('id', 123);
                $response->setTemplate('index');
            });
        });
    }
}