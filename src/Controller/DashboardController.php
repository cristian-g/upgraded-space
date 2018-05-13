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
    public function __invoke(Request $request, Response $response, array $args) {
        $breadcrumb = [];

        $folderSize = null;
        $folderName = null;
        $parentFolder = null;

        if (isset($args['uuid'])) {
            $upload = ($this->container->get('get_folder_by_uuid_use_case'))($args['uuid']);

            if ($upload->getExt() == null) {
                // It is a folder (not the root)
                $folder = $upload;
                $folderId = $folder->getId();
                $folderName = $folder->getName();
                $folderSize = ($this->container->get('get_folder_size_use_case'))($folderId);
                $uploads = ($this->container->get('get_uploads_use_case'))($_SESSION["user_id"], $folderId);
                $this->computeFolderSizes($uploads);

                // Breadcrumb
                array_push($breadcrumb, $folder);
                while ($folder->getIdParent() != null) {
                    $folder = ($this->container->get('get_folder_by_id_use_case'))($folder->getIdParent());
                    array_unshift($breadcrumb, $folder);
                }
                $parentFolder = $breadcrumb[count($breadcrumb)-2];
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
            $this->computeFolderSizes($uploads);
        }

        if ($folderSize == null) $folderSize = 0;

        foreach ($uploads as $key => $upload) {
            $uploads[$key]["updated_at"] = ucfirst(NotificationsController::computeRelativeTime($uploads[$key]["updated_at"]));
        }

        return $this->container->get('view')
            ->render($response, 'dashboard.twig', ['uploads' => $uploads, 'folderName' => $folderName, 'breadcrumb' => $breadcrumb, 'parentFolder' => $parentFolder, 'folderSize' => $folderSize, 'uuid_parent' => (isset($args['uuid'])) ? $args['uuid'] : null ]);
    }

    private function computeFolderSizes(&$uploads) {
        foreach ($uploads as $key => $upload) {
            if ($upload['ext'] == null) {
                // It is a folder
                // Compute its size
                $uploads[$key]['bytes_size'] = ($this->container->get('get_folder_size_use_case'))($upload['id']);
                if ($uploads[$key]['bytes_size'] == null) $uploads[$key]['bytes_size'] = 0;
            }
        }
    }
}