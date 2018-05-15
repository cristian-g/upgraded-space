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
//        if (!isset($_SESSION["user_id"])) {
//            return $response->withStatus(302)->withHeader('Location', '/login');
//        }else{
//            if($_POST['user_id'] != $_SESSION["user_id"]){
//                return $response->withStatus(302)->withHeader('Location', '/logout');
//            }
//        }
      //  $folder = ($this->container->get('get_folder_by_uuid_use_case'))($args['uuid']);

        // Role
     /*   if ($folder->getIdUser() == $_SESSION["user_id"]) {
            $role = 'owner';
        }*/
        // Role
            $folder = ($this->container->get('get_folder_by_uuid_use_case'))($_POST['uuid_upload']);
//        var_dump($folder);
//        die();
            $role = null;
            $share = RoleCalculator::computeRole($folder, $role, $this->container);

       /* $file = __DIR__ . "/log.txt";
        var_dump($_POST['uuid_upload']);
die();
        file_put_contents($file, $content);*/
        return $next($request, $response);
    }
}