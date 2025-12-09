<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->post('/users', [\App\Infrastructure\Http\Controller\CreateUserController::class, 'handle']);

    $r->post('/contacts', [\App\Infrastructure\Http\Controller\CreateContactController::class, 'handle']);
    $r->get('/contacts', [\App\Infrastructure\Http\Controller\ListContactsController::class, 'handle']);
    $r->get('/contacts/{id:\d+}', [\App\Infrastructure\Http\Controller\GetContactController::class, 'handle']);
    $r->put('/contacts/{id:\d+}', [\App\Infrastructure\Http\Controller\UpdateContactController::class, 'handle']);
    $r->delete('/contacts/{id:\d+}', [\App\Infrastructure\Http\Controller\DeleteContactController::class, 'handle']);
};