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

        $parentFolder = ($this->container->get('get_folder_use_case'))($data["uuid_parent"]);

        foreach ($uploadedFiles['files'] as $uploadedFile) {
            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(
                    'An unexpected error ocurred uploading the file %s',
                    $uploadedFile->getClientFilename()
                );
                continue;
            }

            $fileName = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($fileName);

            $extension = $fileInfo['extension'];

            if (!$this->isValidExtension($extension)) {
                $errors[] = sprintf(
                    'Unable to upload the file %s, the extension %s is not valid',
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

        $uploads = ($this->container->get('get_uploads_use_case'))($_SESSION["user_id"]);
        return $this->container->get('view')
            ->render($response, 'dashboard.twig', ['uploads' => $uploads, 'errors' => $errors, 'isPost' => true]);
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
