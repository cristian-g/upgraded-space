<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\UploadedFile;

class EditUserController
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

    function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = "profile_image"; // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        try {

            $data = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();

            //password
            if (!(strlen($data['password']) > 5 and strlen($data['password']) < 13 and
                preg_match('/[A-Z]/', $data['password'])
                and preg_match('/[0-9]/', $data['password']))) {

                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => "Contraseña con formato incorrecto"]);
            }

            //confirm password
            if (strcmp($data['password'], $data['confirm_password']) != 0) {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => "Las dos contraseñas no son iguales"]);
            }

            //email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => "Correo con formato incorrecto"]);
            }

            $user = ($this->container->get('get_user_use_case'))($_SESSION['user_id']);
            $directory = __DIR__.'/../../public/uploads/'.$user->getUuid();

            $service = $this->container->get('edit_user_use_case');

            $profile = $uploadedFiles['profile_image'];

            $fileName = $profile->getClientFilename();
            $fileInfo = pathinfo($fileName);
            $extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;
            if (!$this->isValidExtension($extension) && $profile->getError() === UPLOAD_ERR_OK) {
                return $this->container->get('view')
                    ->render($response, 'profile.twig', ['error' => "Formato de imagen de perfil incorrecto, utilizar .jpg, .png o .gif"]);
            }

            if ($profile->getError() === UPLOAD_ERR_OK and $profile->getSize() <= 500000){
                $path = $directory.'/profile_image.'.$user->getExtension();
                unlink($path);
                $service($data, 0, pathinfo($profile->getClientFilename(), PATHINFO_EXTENSION), $_SESSION['user_id']);
            }else{
                $service($data, 1, null,  $_SESSION['user_id']);
            }

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if ($profile->getError() === UPLOAD_ERR_OK and $profile->getSize() <= 500000) {
                $filename = $this->moveUploadedFile($directory, $profile);
            }

        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'profile.twig', ['error' => 'Error inesperado.']);
        }
        return $response;
    }

    private function isValidExtension($extension)
    {
        if ($extension == null) return false;

        $validExtensions = ['jpg', 'png', 'gif'];

        return in_array($extension, $validExtensions);
    }
}