<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class VerificationMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {
        $key = $request->getAttribute('routeInfo')[2]['key'];
        $user = ($this->container->get('get_user_by_email_activation_key_use_case'))($key);

        if ($user->getActive() == 1) {
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
        return $next($request, $response);
    }
}