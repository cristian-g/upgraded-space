<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUserController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function deleteAction(Request $request, Response $response)
    {
        try{
            $userid = $_SESSION['user_id'];
            $service = $this->container->get('delete_user_use_case');
            $service(["userid" => $userid]);
            $_SESSION['user_id'] = null;
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'profile.twig', ['error' => 'Error inesperado.']);
        }
        return $response->withStatus(302)->withHeader('Location', '/landing');
    }
}