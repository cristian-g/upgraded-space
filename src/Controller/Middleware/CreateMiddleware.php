<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\RoleCalculator;

class CreateMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next) {

        try {
            // El pare (carpeta) on l'usuari vol crear l'ítem
            $parent = ($this->container->get('get_upload_by_uuid_use_case'))($_POST['uuid_parent']);
            $parentId = ($parent == null) ? null : $parent->getId();

            if ($_POST['uuid_parent'] == '') {
                // El vol crear al seu root
                // Té permisos, pot crear ítems
                return $next($request, $response);
            }
            else if ($parentId == null) {
                // L'ítem el vol crear en una carpeta que NO és el seu root
                // Però no s'ha trobat la carpeta que desitja, per tant, accés denegat
                return $response->withStatus(302)->withHeader('Location', '/403');
            }
            else {
                // L'ítem el vol crear en una carpeta que NO és el seu root
                // I la carpeta que desitja l'usuari sí que existeix

                // Obtenim el rol que té l'usuari sobre la carpeta pare
                $role = null;
                $share = RoleCalculator::computeRole($parent, $role, $this->container);

                // Si no és owner i no és admin llavors accés denegat
                if ($role != 'owner' && $role != 'admin') {
                    return $response->withStatus(302)->withHeader('Location', '/403');
                }

                // Si arriba fins aquí vol dir que té permisos, pot crear ítems
                return $next($request, $response);
            }
        }
        catch (\Exception $e) {
            // Per qualsevol excepció, com accedir a una propietat de null, accés denegat, perquè pot ser que l'usuari no estigui loguejat
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
    }
}