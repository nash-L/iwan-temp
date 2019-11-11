<?php
use Sys\Mvc\Request;
use Sys\Mvc\Response;
use Sys\Application;
use Sys\Config;
use Sys\Throwable\Mvc\ResponseThrowable;

$request = Request::createFromGlobals();
$response = Response::create();
try {
    $application = new Application();
    $application->dispatch($request, $response, new Config(ROOT . '/config.php'));
} catch (ResponseThrowable $e) {
    $response->setStatusCode($e->getCode(), $e->getMessage())->setContent(json_encode([
        'code' => $e->getCode(),
        'result' => null,
        'message' => $e->getMessage()
    ]));
} catch (Throwable $e) {
    $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
}
$response->send();
