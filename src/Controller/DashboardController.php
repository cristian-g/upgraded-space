<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DashboardController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $breadcrumb = [];

        $folderSize = null;

        if (isset($args['uuid'])) {
            $upload = ($this->container->get('get_folder_by_uuid_use_case'))($args['uuid']);

            if ($upload->getExt() == null) {
                // It is a folder (not the root)
                $folder = $upload;
                $folderId = $folder->getId();
                $folderSize = ($this->container->get('get_folder_size_use_case'))($folderId);
                $uploads = ($this->container->get('get_uploads_use_case'))($_SESSION["user_id"], $folderId);

                // Breadcrumb
                array_push($breadcrumb, $folder);
                while ($folder->getIdParent() != null) {
                    $folder = ($this->container->get('get_folder_by_id_use_case'))($folder->getIdParent());
                    array_unshift($breadcrumb, $folder);
                }

                // Compute total size of the active folder
                $bytesActiveFolder = ($this->container->get('get_folder_size_use_case'))($folderId);
            }
            else {
                // It is a file
                $file = $upload;
                $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);
                return $response->withStatus(302)->withHeader('Location', '/uploads/'.$user->getUuid().'/'.$file->getUuid());
            }
        }
        else {
            // It is the root folder
            $uploads = ($this->container->get('get_uploads_use_case'))($_SESSION["user_id"]);
        }

        if ($folderSize == null) $folderSize = 0;

        return $this->container->get('view')
            ->render($response, 'dashboard.twig', ['uploads' => $uploads, 'breadcrumb' => $breadcrumb, 'folderSize' => $folderSize, 'uuid_parent' => (isset($args['uuid'])) ? $args['uuid'] : null ]);
    }

    // Compute total size of some uploads
    /*private function computeTotalSize($uploads) {
        $bytesActiveFolder = 0;
        foreach ($uploads as $item) {
            if ($item->getExt() == null) {
                // It is a folder
                += computeTotalSize($uploads);
            }
        }
        return $bytesActiveFolder;
    }*/
}