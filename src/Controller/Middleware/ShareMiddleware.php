<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\RoleCalculator;


class ShareMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        // Role
        $folder = ($this->container->get('get_upload_by_uuid_use_case'))($_POST['uuid_upload']);

        $role = null;
        $share = RoleCalculator::computeRole($folder, $role, $this->container);
        if($role != 'owner'){
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
        return $next($request, $response);
    }
}