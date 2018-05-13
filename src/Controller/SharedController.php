<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SharedController
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
        $shares = ($this->container->get('get_shares_use_case'))($_SESSION["user_id"]);

        foreach ($shares as $key => $share) {
            $upload = ($this->container->get('get_folder_by_id_use_case'))($share['id_upload']);
            $shares[$key]["upload"] = $upload->toArray();

            $uploadUser = ($this->container->get('get_user_use_case'))($upload->getIdUser());
            $shares[$key]["upload"]["user"] = $uploadUser->toArray();

            $shares[$key]["created_at"] = ucfirst(NotificationsController::computeRelativeTime($shares[$key]["created_at"]));
        }

        return $this->container->get('view')
            ->render($response, 'shared.twig', ['shares' => $shares]);
    }
}