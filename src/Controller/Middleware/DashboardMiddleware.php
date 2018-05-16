<?php

namespace Pwbox\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\RoleCalculator;

class DashboardMiddleware
{
    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response, callable $next) {

        try {
            $uuid = (isset($request->getAttribute('routeInfo')[2]['uuid'])) ? $request->getAttribute('routeInfo')[2]['uuid'] : null;

            // La carpeta que l'usuari vol veure
            $folder = ($this->container->get('get_upload_by_uuid_use_case'))($uuid);
            $folderId = ($folder == null) ? null : $folder->getId();

            if (!isset($request->getAttribute('routeInfo')[2]['uuid'])) {
                // Vol veure el seu root
                // Té permís
                return $next($request, $response);
            }
            else if ($folderId == null) {
                // L'usuari vol veure una carpeta que NO és el seu root
                // Però no s'ha trobat la carpeta que desitja, per tant, accés denegat
                return $response->withStatus(302)->withHeader('Location', '/403');
            }
            else {
                // L'usuari vol veure una carpeta que NO és el seu root
                // I la carpeta que desitja veure l'usuari sí que existeix

                // Obtenim el rol que té l'usuari sobre la carpeta que vol veure
                $role = null;
                $share = RoleCalculator::computeRole($folder, $role, $this->container);

                // Si no és owner i no és admin i no és reader llavors accés denegat
                if ($role != 'owner' && $role != 'admin' && $role != 'reader') {
                    return $response->withStatus(302)->withHeader('Location', '/403');
                }

                // Si arriba fins aquí vol dir que té permisos, pot veure la carpeta
                return $next($request, $response);
            }
        }
        catch (\Exception $e) {
            // Per qualsevol excepció, com accedir a una propietat de null, accés denegat, perquè pot ser que l'usuari no estigui loguejat
            return $response->withStatus(302)->withHeader('Location', '/403');
        }
    }
}