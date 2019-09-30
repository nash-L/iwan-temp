<?php


namespace App\Controllers;


use App\Model\Account;
use Sys\Mvc\Response;
use Auryn\InjectionException;
use Sys\Throwable\Mvc\ResponseThrowable;

class Home
{
    /**
     * Home constructor.
     * @throws ResponseThrowable
     */
    public function __construct()
    {
//        throw new ResponseThrowable('message', 401);
    }

    /**
     * @param Response $response
     * @param Account $model
     * @throws InjectionException
     */
    public function index(Response $response, Account $model)
    {
        $response->assign('accounts', $model->select('*'));
        $response->setTemplate('Home/test');
    }
}
