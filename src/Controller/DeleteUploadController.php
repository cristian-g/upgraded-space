<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\EmailSender;
use Pwbox\Controller\utils\RoleCalculator;

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
            
            // Store the name of the item
            $uploadToDelete = ($this->container->get('get_upload_by_id_use_case'))($data["id"]);
            $itemName = '';
            $actionName = '';
            if ($uploadToDelete->getExt() == null) {
                // It is a folder
                $itemFileName = $uploadToDelete->getName();
                $itemName = 'La carpeta';
                $actionName = 'eliminada';
            }
            else {
                // It is a file
                $itemFileName = $uploadToDelete->getName().'.'.$uploadToDelete->getExt();
                $itemName = 'El archivo';
                $actionName = 'eliminado';
            }

            // Delete from database
            $service = $this->container->get('delete_upload_use_case');
            $service($data['id']);

            // Post notification and send email. Type: upload_deleted - Ítem eliminado
            // Post notification
            $service = $this->container->get('post_notification_use_case');
            $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

            // Role
            $folder = ($this->container->get('get_upload_by_uuid_use_case'))($data["uuid_parent"]);
            $role = null;
            $share = RoleCalculator::computeRole($folder, $role, $this->container);
            if ($share != null && $role == 'admin') {
                $idShare = $share->getId();
                $sharedFolder = ($this->container->get('get_upload_by_id_use_case'))($share->getIdUpload());

                // Post notification
                $message = $itemName.' con el nombre "'.$itemFileName.'" ha sido '.$actionName.' por '.$user->getUsername().' ('.$user->getEmail().'), que es administrador de tu carpeta compartida llamada "'.$sharedFolder->getName().'".';
                $owner = ($this->container->get('get_user_use_case'))($sharedFolder->getIdUser());
                $service([
                    'idShare' => $idShare,
                    'type' => 'upload_deleted',
                    'message' => $message
                ]);
                // Send email
                $notificationTitle = 'Ítem eliminado';
                $notificationMessage = $message;
                $folderName = $sharedFolder->getName();
                $folderLink = "http://pwbox.test/dashboard/".$sharedFolder->getUuid();
                $notificationsLink = "http://pwbox.test/notifications";
                $userEmail = $owner->getEmail();
                $userUsername = $owner->getUsername();
                EmailSender::sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername);
            }

            $this->container->get('flash')->addMessage('dashboard', 'El ítem se ha eliminado correctamente.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        } catch (\Exception $e) {
            return $this->container->get('view')
                ->render($response, 'dashboard.twig', ['error' => 'Error inesperado.']);
        }
        return $response;
    }
}