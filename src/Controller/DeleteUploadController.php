<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUploadController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUploadController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function postAction(Request $request, Response $response) {
        try {
            $data = $request->getParsedBody();

            // Delete from hard disk if exists child files
            $files = ($this->container->get('get_child_files_use_case'))($data['id']);
            foreach ($files as $file) {
                $user = ($this->container->get('get_user_use_case'))($file->getIdUser());
                $directory = __DIR__.'/../../public/uploads/'.$user->getUuid();
                unlink($directory . DIRECTORY_SEPARATOR . $file->getUuid());
            }

            // Delete from database
            $service = $this->container->get('delete_upload_use_case');
            $service($data['id']);

            $this->container->get('flash')->addMessage('dashboard', 'El ítem se ha eliminado correctamente.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        } catch (\Exception $e) {
            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['error' => $e->getMessage()]);
        }
        return $response;
    }
}