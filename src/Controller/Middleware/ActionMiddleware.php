<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\RoleCalculator;

class ActionMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next) {

        try {
            // El pare (carpeta) on l'usuari diu que es troba l'ítem que vol renombrar o esborrar
            $parent = ($this->container->get('get_upload_by_uuid_use_case'))($_POST['uuid_parent']);
            $parentId = ($parent == null) ? null : $parent->getId();

            // L'ítem que l'usuari vol renombrar o esborrar
            $upload = ($this->container->get('get_upload_by_id_use_case'))($_POST['id']);

            // Comprovem que l'ítem que l'usuari vol renombrar o esborrar estigui on diu l'usuari
            if ($upload->getIdParent() != $parentId) {
                // Accés denegat
                return $response->withStatus(302)->withHeader('Location', '/403');
            }

            if ($upload->getIdParent() == null && $parentId == null) {
                // L'ítem que l'usuari vol renombrar o esborrar està al seu root
                // Per tant simplement mirem que sigui el seu creador

                if ($upload->getIdUser() == $_SESSION["user_id"]) {
                    // Té permisos, pot renombrar o esborrar
                    return $next($request, $response);
                }
                else {
                    // Accés denegat
                    return $response->withStatus(302)->withHeader('Location', '/403');
                }
            }
            else {
                // L'ítem que l'usuari vol renombrar o esborrar NO està al seu root

                // Obtenim el rol que té l'usuari sobre la carpeta pare
                $role = null;
                $share = RoleCalculator::computeRole($parent, $role, $this->container);

                // Si no és owner i no és admin llavors accés denegat
                if ($role != 'owner' && $role != 'admin') {
                    return $response->withStatus(302)->withHeader('Location', '/403');
                }

                // Si arriba fins aquí vol dir que té permisos, pot renombrar o esborrar
                return $next($request, $response);
            }
        }
        catch (\Exception $e) {
            // Per qualsevol excepció, com accedir a una propietat de null, accés denegat, perquè pot ser que l'usuari no estigui loguejat
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
    }
}