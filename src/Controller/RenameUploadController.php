<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\EmailSender;
use Pwbox\Controller\utils\RoleCalculator;

class RenameUploadController
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

            if ($data["name"] == "" || $data["name"] == null) {
                $this->container->get('flash')->addMessage('dashboard-errors', 'El ítem no se ha podido renombrar porque el nombre especificado está vacío.');
                return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
            }

            // Store upload to know its old name
            $oldUpload = ($this->container->get('get_upload_by_id_use_case'))($data["id"]);
            if ($oldUpload->getExt() == null) {
                // It is a folder
                $oldName = $oldUpload->getName();
            }
            else {
                // It is a file
                $oldName = $oldUpload->getName().'.'.$oldUpload->getExt();
            }

            // Rename upload
            $service = $this->container->get('rename_upload_use_case');
            $service($data);

            // New name
            $newUpload = ($this->container->get('get_upload_by_id_use_case'))($data["id"]);
            $itemName = '';
            $actionName = '';
            if ($newUpload->getExt() == null) {
                // It is a folder
                $newName = $newUpload->getName();
                $itemName = 'La carpeta';
                $actionName = 'renombrada';
            }
            else {
                // It is a file
                $newName = $newUpload->getName().'.'.$newUpload->getExt();
                $itemName = 'El archivo';
                $actionName = 'renombrado';
            }

            // Post notification and send email. Type: upload_renamed - Ítem renombrado
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
                $message = $itemName.' con el anterior nombre "'.$oldName.'" ha sido '.$actionName.' a "'.$newName.'" por '.$user->getUsername().' ('.$user->getEmail().'), que es administrador de tu carpeta compartida llamada "'.$sharedFolder->getName().'".';
                $owner = ($this->container->get('get_user_use_case'))($sharedFolder->getIdUser());
                $service([
                    'idShare' => $idShare,
                    'type' => 'upload_renamed',
                    'message' => $message
                ]);
                // Send email
                $notificationTitle = 'Ítem renombrado';
                $notificationMessage = $message;
                $folderName = $sharedFolder->getName();
                $folderLink = "http://pwbox.test/dashboard/".$sharedFolder->getUuid();
                $notificationsLink = "http://pwbox.test/notifications";
                $userEmail = $owner->getEmail();
                $userUsername = $owner->getUsername();
                EmailSender::sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername);
            }

            $this->container->get('flash')->addMessage('dashboard', 'El ítem ha sido renombrado correctamente.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        }
        catch (\Exception $e) {
            $this->container->get('flash')->addMessage('dashboard-errors', 'Error inesperado.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        }
        return $response;
    }
}