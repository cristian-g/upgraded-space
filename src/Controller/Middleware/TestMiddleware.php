<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TestMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $response->getBody()->write('BEFORE');
        $next($request, $response);//següent middleware. hi ha un altre middleware? no, doncs crida a la ruta, al controlador
        $response->getBody()->write('AFTER');
        return $response;
    }
}