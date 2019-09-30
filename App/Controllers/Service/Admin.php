<?php


namespace App\Controllers\Service;


use Sys\Mvc\Response;

class Admin
{
    function index(Response $response, int $id = 123)
    {
        $response->assign('id', $id);
    }
}