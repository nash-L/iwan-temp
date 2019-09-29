<?php
use Sys\Mvc\Request;
use Sys\Mvc\Response;
use Sys\Application;
use Sys\Config;

$request = Request::createFromGlobals();
$response = Response::create();
try {
    $application = new Application($request, $response);
    $application->dispatch(new Config(ROOT . '/config.php'));
} catch (Exception $e) {
    $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
}
$response->send();
