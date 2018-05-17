<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Pwbox\Controller\utils\EmailSender;

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

            $folder = ($this->container->get('get_upload_by_uuid_use_case'))($data["uuid_upload"]);
            $data["idUpload"] = $folder->getId();

            $service = $this->container->get('get_from_email_use_case');
            $userDestination = $service($data['email']);
            if ($userDestination->getId() == null) {
                $this->container->get('flash')->addMessage('dashboard-errors', 'La carpeta no se ha podido compartir porque no existe un usuario con el email especificado.');
                return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
            }
            else {
                // Check if this folder is already shared
                $share = ($this->container->get('get_share_by_upload_id_use_case'))($folder->getId(), $userDestination->getId());
                if ($share->getId() != null) {
                    $this->container->get('flash')->addMessage('dashboard-errors', 'Esta carpeta ya está compartida con el usuario con el email especificado.');
                    return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
                }
            }

            $data["idUserDestination"] = $userDestination->getId();

            $service = $this->container->get('post_share_use_case');
            $idShare = $service($data);

            $service = $this->container->get('post_notification_use_case');

            $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

            // Post notifications and send emails
            $roleText = '';
            if ($data["role"] == 'reader') {
                $roleText = 'lector';
            }
            else if ($data["role"] == 'admin') {
                $roleText = 'administrador';
            }
            // Carpeta recibida
            $message = $user->getUsername().' ('.$user->getEmail().') compartió la carpeta llamada "'.$folder->getName().'" contigo con rol de '.$roleText.'.';
            $service([
                'idShare' => $idShare,
                'type' => 'folder_received',
                'message' => $message
            ]);
            $notificationTitle = "Carpeta recibida";
            $notificationMessage = $message;
            $folderName = $folder->getName();
            $folderLink = "http://pwbox.test/dashboard/".$folder->getUuid();
            $notificationsLink = "http://pwbox.test/notifications";
            $userEmail = $userDestination->getEmail();
            $userUsername = $userDestination->getUsername();
            EmailSender::sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername);
            // Carpeta compartida
            $message = 'Compartiste la carpeta llamada "'.$folder->getName().'" con '.$userDestination->getUsername().' ('.$userDestination->getEmail().') con rol de '.$roleText.'.';
            $service([
                'idShare' => $idShare,
                'type' => 'folder_sended',
                'message' => $message
            ]);
            $notificationTitle = "Carpeta compartida";
            $notificationMessage = $message;
            $folderName = $folder->getName();
            $folderLink = "http://pwbox.test/dashboard/".$folder->getUuid();
            $notificationsLink = "http://pwbox.test/notifications";
            $userEmail = $user->getEmail();
            $userUsername = $user->getUsername();
            EmailSender::sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername);

            $this->container->get('flash')->addMessage('dashboard', 'La carpeta se ha compartido correctamente.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        } catch (\Exception $e){
            $this->container->get('flash')->addMessage('dashboard-errors', 'Error inesperado.');
            return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
        }
        return $response;
    }
}