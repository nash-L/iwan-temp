<?php


namespace App\Controllers\Service\Admin;


use Sys\Mvc\Response;

class Home
{
    function index(Response $response)
    {
        $response->assign('key', 123);
    }
}