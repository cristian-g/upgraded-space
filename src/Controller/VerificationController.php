<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class VerificationController
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

        $user = ($this->container->get('get_user_by_email_activation_key_use_case'))($args['key']);

        if ($user->getEmailActivationKey() == $args['key']) {
            $service = $this->container->get('activate_user_use_case');
            $service($user);

            $this->container->get('flash')->addMessage('dashboard', 'Tu dirección de correo electrónico se ha confirmado correctamente.');

            // Log in
            $_SESSION["user_id"] = $user->getId();

            return $response->withStatus(302)->withHeader('Location', '/dashboard');
        }
        else {
            return $response->withStatus(302)->withHeader('Location', '/403');
        }

    }
}