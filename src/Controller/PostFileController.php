<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class PostFileController
{
    protected $container;
    public static $max_size_file = 2000000;
    public static $max_size_disk = 1000000000;

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

        $parentFolder = ($this->container->get('get_folder_by_uuid_use_case'))($data["uuid_parent"]);

        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    'Ha ocurrido un error al subir el archivo %s',
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            if ($uploadedFile->getSize() > $this->max_size_file){
                $errors[] = sprintf(
                    'El tamaño supera los 2MB, no se ha podido subir el archivo %s  %s  %s',
                    $uploadedFile->getClientFilename(),
                    $uploadedFile->getSize()
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

            // Save the uploaded file in the database
            $service = $this->container->get('post_file_use_case');
            $id = $service($fileInfo, $parentFolder->getId());

            $upload = ($this->container->get('get_file_use_case'))($id);

            // Move the file to the user folder
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $upload->getUuid());
        }

        $uploads = ($this->container->get('get_uploads_use_case'))(null, $_SESSION["user_id"]);

        $this->container->get('flash')->addMessage('errors', $errors);
        $this->container->get('flash')->addMessage('isPost', true);
        return $response->withStatus(302)->withHeader('Location', '/dashboard'.(($data["uuid_parent"] != null) ? '/'.$data["uuid_parent"] : null));

    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['pdf', 'jpg', 'png', 'gif', 'md', 'txt'];

        return in_array($extension, $validExtensions);
    }
}
