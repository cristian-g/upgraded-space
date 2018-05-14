<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\EmailSender;

class ResendVerificationController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(Request $request, Response $response, array $args) {

        $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

        // Get account verification link
        $verificationLink = 'http://' . $_SERVER['SERVER_NAME'] . '/verification/' . $user->getEmailActivationKey();

        EmailSender::sendVerificationRequest($verificationLink, $user->getEmail(), $user->getUsername());

        $this->container->get('flash')->addMessage('profile', 'El enlace para activar la dirección de correo electrónico se ha enviado de nuevo correctamente.');

        return $response->withStatus(302)->withHeader('Location', '/profile');
    }
}