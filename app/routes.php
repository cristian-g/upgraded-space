<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->add('Pwbox\Controller\Middleware\SessionMiddleware');

$app->get(
    '/hello/{name}',
    'Pwbox\Controller\HelloController'// es pot posar simplement HelloController si la classe és invocable
);
    //->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');//podríem afegir un altre middleware add('Pwbox\Controller\Middleware\TestMiddleware'), aquest afegit s'executaria el primer

$app->get('/', function ($req, $res, $args) {
    return $res->withStatus(302)->withHeader('Location', '/landing');
});

$app->get(
    '/landing',
    'Pwbox\Controller\LandingController'
);

$app->get(
    '/register',
    'Pwbox\Controller\PostUserController:indexAction'
);

$app->post(
    '/register',
    'Pwbox\Controller\PostUserController:registerAction'
);

$app->get(
    '/dashboard',
    'Pwbox\Controller\DashboardController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/logout',
    'Pwbox\Controller\LogOutController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');
