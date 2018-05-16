<?php

$app->add('Pwbox\Controller\Middleware\SessionMiddleware');

$app->get(
    '/',
    'Pwbox\Controller\LandingController'
)->add('Pwbox\Controller\Middleware\LoggedRedirectMiddleware');// Si ja ha iniciat sessió, porta l'usuari al dashboard

$app->get(
    '/register',
    'Pwbox\Controller\PostUserController:indexAction'
)->add('Pwbox\Controller\Middleware\LoggedRedirectMiddleware');// Si ja ha iniciat sessió, porta l'usuari al dashboard

$app->post(
    '/register',
    'Pwbox\Controller\PostUserController:registerAction'
)->add('Pwbox\Controller\Middleware\LoggedRedirectMiddleware');// Si ja ha iniciat sessió, porta l'usuari al dashboard

$app->post(
    '/edit',
    'Pwbox\Controller\EditUserController'
)->add('Pwbox\Controller\Middleware\AuthorizedMiddleware');// Si no ha iniciat sessió, porta l'usuari a la pàgina d'accés denegat

$app->get(
    '/login',
    'Pwbox\Controller\LogInController:indexAction'
)->add('Pwbox\Controller\Middleware\LoggedRedirectMiddleware');// Si ja ha iniciat sessió, porta l'usuari al dashboard

$app->post(
    '/login',
    'Pwbox\Controller\LogInController:LogInAction'
)->add('Pwbox\Controller\Middleware\LoggedRedirectMiddleware');// Si ja ha iniciat sessió, porta l'usuari al dashboard

$app->get(
    '/dashboard[/{uuid}]',
    'Pwbox\Controller\DashboardController'
)->add('Pwbox\Controller\Middleware\DashboardMiddleware')// Només pot accedir a les carpetes de les que l'usuari és owner, admin o reader
->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Si no ha iniciat sessió, no pot accedir

$app->get(
    '/profile',
    'Pwbox\Controller\ProfileUserController'
)->add('Pwbox\Controller\Middleware\AuthorizedMiddleware');// Si no ha iniciat sessió, porta l'usuari a la pàgina d'accés denegat

$app->get(
    '/logout',
    'Pwbox\Controller\LogOutController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Només permet l'accés si l'usuari ha iniciat sessió

$app->post(
    '/file',
    'Pwbox\Controller\PostFileController:postAction'
)->add('Pwbox\Controller\Middleware\CreateMiddleware');// Només pot pujar arxius a les carpetes de les que l'usuari és owner o admin

$app->post(
    '/deleteUser',
    'Pwbox\Controller\DeleteUserController:deleteAction'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Només permet l'accés si l'usuari ha iniciat sessió

$app->post(
    '/folder',
    'Pwbox\Controller\PostFolderController:postAction'
)->add('Pwbox\Controller\Middleware\CreateMiddleware');// Només pot crear carpetes en les carpetes de les que l'usuari és owner o admin

$app->post(
    '/rename',
    'Pwbox\Controller\RenameUploadController:postAction'
)->add('Pwbox\Controller\Middleware\ActionMiddleware');// Només pot renombrar arxius o carpetes que es troben en carpetes de les que l'usuari és owner o admin

$app->post(
    '/delete',
    'Pwbox\Controller\DeleteUploadController:postAction'
)->add('Pwbox\Controller\Middleware\ActionMiddleware');// Només pot esborrar arxius o carpetes que es troben en carpetes de les que l'usuari és owner o admin

$app->post(
    '/share',
    'Pwbox\Controller\PostShareController:postAction'
)->add('Pwbox\Controller\Middleware\ShareMiddleware');// Només pot compartir carpetes de les que l'usuari és owner

$app->get(
    '/shared',
    'Pwbox\Controller\SharedController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Només permet l'accés si l'usuari ha iniciat sessió

$app->get(
    '/notifications',
    'Pwbox\Controller\NotificationsController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Només permet l'accés si l'usuari ha iniciat sessió

$app->get(
    '/verification/{key}',
    'Pwbox\Controller\VerificationController'
);

$app->get(
    '/resend',
    'Pwbox\Controller\ResendVerificationController'
)->add('Pwbox\Controller\Middleware\UserLoggedMiddleware');// Només permet l'accés si l'usuari ha iniciat sessió

$app->get(
    '/403',
    'Pwbox\Controller\NotAuthorizedController'
);