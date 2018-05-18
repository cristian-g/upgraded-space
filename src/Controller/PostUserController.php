<?php

namespace Pwbox\Controller;

use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Pwbox\Controller\utils\EmailSender;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\UploadedFileInterface;
use Slim\Http\UploadedFile;
use Ramsey\Uuid\Uuid;

class PostUserController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * PostUserController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response){
        $messages = $this->container->get('flash')->getMessages();
        $registerMessages = isset($messages['register'])?$messages['register']:[];
        return $this->container->get('view')
            ->render($response, 'register.twig', ['messages' => $registerMessages]);
    }

    function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = "profile_image"; // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }


    public function registerAction(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();
            $uploadedFiles = $request->getUploadedFiles();

            //password
            if (!(strlen($data['password']) > 5 and strlen($data['password']) < 13 and
                preg_match('/[A-Z]/', $data['password'])
                and preg_match('/[0-9]/', $data['password']))) {

                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Contraseña con formato incorrecto"]);
            }

            //confirm password
            if (strcmp($data['password'], $data['confirm_password']) != 0) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Las dos contraseñas no son iguales"]);
            }

            //username
            if (!(ctype_alnum($data['username']) and strlen($data['username']) > 0 and strlen($data['username']) < 21)) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Nombre de usuario con formato incorrecto"]);
            }

            //email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Correo con formato incorrecto"]);
            }

            //birthdate
            $birthdate = explode("-", $data['birthdate']);
            if(!checkdate($birthdate[1], $birthdate[2], $birthdate[0]) or $birthdate[0] > date("Y") or ($birthdate[0] == date("Y") and $birthdate[1] > date("m")) or ($birthdate[0] == date("Y") and $birthdate[1] == date("m") and $birthdate[2] > date("d"))){
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Fecha de nacimiento con formato incorrecto"]);
            }


            $service = $this->container->get('post_user_use_case');

            $profile = $uploadedFiles['profile_image'];

            //we check profile image extension
            $fileName = $profile->getClientFilename();
            $fileInfo = pathinfo($fileName);
            $extension = isset($fileInfo['extension']) ? $fileInfo['extension'] : null;
            if (!$this->isValidExtension($extension) && $profile->getError() === UPLOAD_ERR_OK) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "El formato de la imagen de perfil es incorrecto. Por favor, utiliza .jpg, .png o .gif"]);
            }

            if ($profile->getError() === UPLOAD_ERR_OK && $profile->getSize() <= 500000){
                $userId = $service($data, 0, pathinfo($profile->getClientFilename(), PATHINFO_EXTENSION));
            }else{
                $userId = $service($data, 1, 'jpg');
            }

            $user = ($this->container->get('get_user_use_case'))($userId);

            $directory = __DIR__.'/../../public/uploads/'.$user->getUuid();// És relatiu o absolut, però respecte el file system (la màquina)
            $directory_default = __DIR__.'/../../public/assets/img/profile_images/default.jpg';

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if ($profile->getError() === UPLOAD_ERR_OK && $profile->getSize() <= 500000) {
                $filename = $this->moveUploadedFile($directory, $profile);
                $this->container->get('flash')->addMessage('login', 'El usuario ha sido registrado correctamente. Por favor, inicia sesión o utiliza el link de activación que hemos enviado a tu email.');
            }else{
                if ( $profile->getSize() > 500000){
                    return $this->container->get('view')
                        ->render($response, 'register.twig', ['error' => "La imagen de perfil es demasiado grande. El tamaño máximo es de 500 KB."]);
                }
                else if (copy($directory_default, $directory.'/profile_image.jpg')){
                    $this->container->get('flash')->addMessage('login', 'El usuario ha sido registrado correctamente. Por favor, inicia sesión o utiliza el link de activación que hemos enviado a tu email.');
                }
            }

            // Create account verification link
            $verificationLink = 'http://' . $_SERVER['SERVER_NAME'] . '/verification/' . $user->getEmailActivationKey();

            EmailSender::sendVerificationRequest($verificationLink, $user->getEmail(), $user->getUsername());

            return $response->withStatus(302)->withHeader('Location', '/login');

        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'register.twig', ['error' => 'Error inesperado.']);
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