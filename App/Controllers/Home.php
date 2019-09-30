<?php


namespace App\Controllers;


use Sys\Mvc\Response;
use Sys\Throwable\Mvc\ResponseThrowable;

class Home
{
    /**
     * Home constructor.
     * @throws ResponseThrowable
     */
    public function __construct()
    {
        throw new ResponseThrowable('message', 401);
    }

    public function index(Response $response)
    {
        $response->assign('id', 12);
    }
}
