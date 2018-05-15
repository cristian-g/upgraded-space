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
    if (isset($_SESSION["user_id"])) {
        return $res->withStatus(302)->withHeader('Location', '/dashboard');
    }
    else {
        return $res->withStatus(302)->withHeader('Location', '/landing');
    }
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

$app->post(
    '/edit',
    'Pwbox\Controller\EditUserController'
);

$app->get(
    '/login',
    'Pwbox\Controller\LogInController:indexAction'
);

$app->post(
    '/login',
    'Pwbox\Controller\LogInController:LogInAction'
);

$app->get(
    '/dashboard[/{uuid}]',
    'Pwbox\Controller\DashboardController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/profile',
    'Pwbox\Controller\ProfileUserController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/logout',
    'Pwbox\Controller\LogOutController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->post(
    '/file',
    'Pwbox\Controller\PostFileController:postAction'
);

$app->post(
    '/deleteUser',
    'Pwbox\Controller\DeleteUserController:deleteAction'
);

$app->post(
    '/folder',
    'Pwbox\Controller\PostFolderController:postAction'
);

$app->post(
    '/rename',
    'Pwbox\Controller\RenameUploadController:postAction'
);

$app->post(
    '/delete',
    'Pwbox\Controller\DeleteUploadController:postAction'
);

$app->post(
    '/share',
    'Pwbox\Controller\PostShareController:postAction'
);

$app->get(
    '/shared',
    'Pwbox\Controller\SharedController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/notifications',
    'Pwbox\Controller\NotificationsController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');

$app->get(
    '/verification/{key}',
    'Pwbox\Controller\VerificationController'
);

$app->get(
    '/resend',
    'Pwbox\Controller\ResendVerificationController'
);