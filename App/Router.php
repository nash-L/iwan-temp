<?php


namespace App;


use App\Controllers\Account;
use Sys\Mvc\Request;

class Router extends \Sys\Router
{
    public function define(Request $request)
    {
        $this->post('/login', [Account::class, 'login']);
    }
}