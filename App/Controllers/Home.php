<?php


namespace App\Controllers;


use Sys\Mvc\Response;
use Sys\Throwable\Mvc\ResponseThrowable;

class Home
{
    /**
     * Home constructor.
     * @param Response $response
     * @throws ResponseThrowable
     */
    public function __construct(Response $response)
    {
//        throw new ResponseThrowable('message', 401);
    }

    public function test(Response $response)
    {
        $response->assign('id', 12);
    }
}
