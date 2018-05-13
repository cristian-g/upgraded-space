<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostShareController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUploadController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postAction(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();

            $folder = ($this->container->get('get_folder_by_uuid_use_case'))($data["uuid_upload"]);
            $data["idUpload"] = $folder->getId();

            $service = $this->container->get('get_from_email_use_case');
            $userDestination = $service($data['email']);
            $data["idUserDestination"] = $userDestination->getId();

            $service = $this->container->get('post_share_use_case');
            $idShare = $service($data);

            $service = $this->container->get('post_notification_use_case');

            // Post notifications
            $service([
                'idShare' => $idShare,
                'type' => 'folder_received',
                'message' => 'Han compartido una carpeta contigo.'
            ]);
            $service([
                'idShare' => $idShare,
                'type' => 'folder_sended',
                'message' => 'Has compartido una carpeta.'
            ]);

            $this->container->get('flash')->addMessage('dashboard', 'La carpeta se ha compartido correctamente.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['error' => $e->getMessage()]);
        }
        return $response;
    }
}
