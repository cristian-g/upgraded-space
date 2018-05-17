<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Pwbox\Controller\utils\EmailSender;
use Pwbox\Controller\utils\RoleCalculator;

class PostFileController
{
    protected $container;
    public static $max_size_file = 2000000;// 2000000 bytes = 2 MB
    public static $max_size_disk = 9000000;// 1000000000 bytes = 1 GB

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function postAction(Request $request, Response $response)
    {
        $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

        $directory = __DIR__.'/../../public/uploads/'.$user->getUuid();// És relatiu o absolut, però respecte el file system (la màquina)

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $uploadedFiles = $request->getUploadedFiles();
        $data = $request->getParsedBody();

        $errors = [];
        $succes = [];

        $parentFolder = ($this->container->get('get_upload_by_uuid_use_case'))($data["uuid_parent"]);

        $numCorrectFiles = 0;
        $fileNames = [];
        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    'Ha ocurrido un error al subir el archivo %s',
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            if ($uploadedFile->getSize() > self::$max_size_file) {
                $errors[] = sprintf(
                    'El tamaño supera los 2MB, no se ha podido subir el archivo %s',
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            $fileName = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($fileName);

            /**
             * Retrieve the file size.
             *
             * Implementations SHOULD return the value stored in the "size" key of
             * the file in the $_FILES array if available, as PHP calculates this based
             * on the actual size transmitted.
             *
             * @return int|null The file size in bytes or null if unknown.
             */
            $fileInfo['size'] = $uploadedFile->getSize();

            $extension = $fileInfo['extension'];

            if (!$this->isValidExtension($extension)) {
                $errors[] = sprintf(
                    'El archivo %s no se ha podido subir porque la extensión %s no es válida.',
                    $fileName,
                    $extension
                );
                continue;
            }

            //we check available capacity to see if we can upload
            $rootFolderSize = ($this->container->get('get_root_folder_size_use_case'))($_SESSION["user_id"]);
            if ($rootFolderSize + $uploadedFile->getSize() > self::$max_size_disk){
                $errors[] = sprintf(
                    'El archivo %s no se ha podido subir porque la extensión %s no es válida.',
                    $fileName,
                    $extension
                );
                continue;
            }

            // Save the uploaded file in the database
            $service = $this->container->get('post_file_use_case');
            $id = $service($fileInfo, $parentFolder->getId());

            $upload = ($this->container->get('get_file_use_case'))($id);

            // Move the file to the user folder
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $upload->getUuid());

            $numCorrectFiles++;
            $fileNames[] = $fileInfo['filename'].'.'.$fileInfo['extension'];

            $succes[] = sprintf(
                '%s se ha subido correctamente',
                $fileName
            );
        }

        $uploads = ($this->container->get('get_uploads_use_case'))(null, $_SESSION["user_id"]);

        if ($numCorrectFiles > 0) {
            // Post notification and send email. Type: new_uploads - Nuevos ítems
            // Post notification
            $service = $this->container->get('post_notification_use_case');
            $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

            $fileNamesText = implode(", ", $fileNames);

            // Role and notificate
            $folder = ($this->container->get('get_upload_by_uuid_use_case'))($data["uuid_parent"]);
            $role = null;
            $share = RoleCalculator::computeRole($folder, $role, $this->container);
            if ($share != null && $role == 'admin') {
                $idShare = $share->getId();
                $sharedFolder = ($this->container->get('get_upload_by_id_use_case'))($share->getIdUpload());

                // Post notification
                $type = ($numCorrectFiles > 1) ? 'new_uploads' : 'new_upload';
                if ($numCorrectFiles > 1) {
                    $message = 'Se han subido '.$numCorrectFiles.' nuevos archivos ('.$fileNamesText.') por '.$user->getUsername().' ('.$user->getEmail().'), que es administrador de tu carpeta compartida llamada "'.$sharedFolder->getName().'".';
                }
                else {
                    $message = 'Se ha subido 1 nuevo archivo ('.$fileNames[0].') por '.$user->getUsername().' ('.$user->getEmail().'), que es administrador de tu carpeta compartida llamada "'.$sharedFolder->getName().'".';
                }
                $owner = ($this->container->get('get_user_use_case'))($sharedFolder->getIdUser());
                $service([
                    'idShare' => $idShare,
                    'type' => $type,
                    'message' => $message
                ]);
                // Send email
                $notificationTitle = ($numCorrectFiles > 1) ? 'Nuevos ítems' : 'Nuevo ítem';
                $notificationMessage = $message;
                $folderName = $sharedFolder->getName();
                $folderLink = "http://pwbox.test/dashboard/".$sharedFolder->getUuid();
                $notificationsLink = "http://pwbox.test/notifications";
                $userEmail = $owner->getEmail();
                $userUsername = $owner->getUsername();
                EmailSender::sendNotification($notificationTitle, $notificationMessage, $folderName, $folderLink, $notificationsLink, $userEmail, $userUsername);
            }
        }


        $this->container->get('flash')->addMessage('errors', $errors);
        $this->container->get('flash')->addMessage('isPost', true);
        $this->container->get('flash')->addMessage('succes', $succes);
        return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));
    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension($extension)
    {
        if ($extension == null) return false;

        $validExtensions = ['pdf', 'jpg', 'png', 'gif', 'md', 'txt'];

        return in_array($extension, $validExtensions);
    }
}
