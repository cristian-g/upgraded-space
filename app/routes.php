<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->add('Pwbox\Controller\Middleware\SessionMiddleware');

$app->get(
    '/hello/{name}',
    'Pwbox\Controller\HelloController'// es pot posar simplement HelloController si la classe és invocable
);
    //->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');//podríem afegir un altre middleware add('Pwbox\Controller\Middleware\TestMiddleware'), aquest afegit s'executaria el primer

$app->get(
    '/landing',
    'Pwbox\Controller\LandingController'
);

$app->post(
    '/user',
    'Pwbox\Controller\PostUserController:registerAction'
);

$app->get(
    '/user',
    'Pwbox\Controller\PostUserController:indexAction'
);