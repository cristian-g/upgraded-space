<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get(
    '/hello/{name}',
    'Pwbox\Controller\HelloController:indexAction'// es pot posar simplement HelloController si la classe és invocable
)->add('Pwbox\Controller\Middleware\TestMiddleware');//podríem afegir un altre middleware add('Pwbox\Controller\Middleware\TestMiddleware'), aquest afegit s'executaria el primer

$app->post(
    '/user',
    'Pwbox\Controller\PostUserController'
);