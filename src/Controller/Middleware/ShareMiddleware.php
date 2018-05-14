<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ShareMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
//        if (!isset($_SESSION["user_id"])) {
//            return $response->withStatus(302)->withHeader('Location', '/login');
//        }else{
//            if($_POST['user_id'] != $_SESSION["user_id"]){
//                return $response->withStatus(302)->withHeader('Location', '/logout');
//            }
//        }

        $folder = ($this->container->get('get_folder_by_uuid_use_case'))($args['uuid']);

        // Role
        if ($folder->getIdUser() == $_SESSION["user_id"]) {
            $role = 'owner';
        }
        return $next($request, $response);
    }
}