<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthorizedMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        if (!isset($_SESSION["user_id"])) {
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
        return $next($request, $response);
    }
}