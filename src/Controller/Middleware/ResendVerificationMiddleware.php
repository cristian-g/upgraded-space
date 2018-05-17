<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ResendVerificationMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

        if ($user->getActive() == 1) {
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
        return $next($request, $response);
    }
}