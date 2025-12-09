<?php

use FastRoute\Dispatcher;
use Laminas\Diactoros\ServerRequestFactory;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, X-User-Id, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$container = require __DIR__ . '/../config/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(require __DIR__ . '/../config/routes.php');

$request = ServerRequestFactory::fromGlobals();

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
        
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
        
    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        [$class, $method] = $handler;
        
        $controller = $container->get($class);
        
        $response = $controller->$method($request, $vars);
        
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();
        break;
}