<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class FileController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAction(Request $request, Response $response) {
        return $this->container->get('view')
            ->render($response, 'file.twig',[]);
    }

    public function postAction(Request $request, Response $response)
    {
        $directory = __DIR__.'/../../public/uploads';// És relatiu o absolut, però respecte el file system (la màquina)

        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];

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
            $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);
        }

        return $this->container->get('view')
            ->render($response, 'file.twig', ['errors' => $errors, 'isPost' => true]);
    }

    /**
     * Validate the extension of the file
     *
     * @param string $extension
     * @return boolean
     */
    private function isValidExtension(string $extension)
    {
        $validExtensions = ['jpg', 'png'];

        return in_array($extension, $validExtensions);
    }
}
