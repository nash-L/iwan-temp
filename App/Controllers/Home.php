<?php


namespace App\Controllers;


use Sys\Mvc\Response;

class Home
{
    public function test(Response $response)
    {
        $response->assign('id', 12);
    }
}
