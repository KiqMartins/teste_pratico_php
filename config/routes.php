<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->post('/users', [\App\Infrastructure\Http\Controller\CreateUserController::class, 'handle']);
    
};