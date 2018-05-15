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
                    ->render($response, 'profile.twig', ['error' => "Las dos contrasenyas no son iguales"]);
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
            if ($profile->getError() === UPLOAD_ERR_OK){
                $path = $directory.'/profile_image.'.$user->getExtension();
                unlink($path);
                $service($data, 0, pathinfo($profile->getClientFilename(), PATHINFO_EXTENSION), $_SESSION['user_id']);
            }else{
                $service($data, 1, null,  $_SESSION['user_id']);
            }

            $this->container->get('flash')->addMessage('dashboard', 'User registered.');



            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            if ($profile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $profile);
                $this->container->get('flash')->addMessage('login', 'User registered with profile image.');
            }

            // create account verification link
            $link = 'http://' . $_SERVER['SERVER_NAME'] . '/activation.php?key=' . 'EXAMPLE';

            // get the html email content
            $directory = __DIR__ . '/../view/emails/';
            $html_content = file_get_contents($directory . 'email_verification.html');
            echo $html_content;
            /*$html_content = preg_replace('/{link}/', $link, $html_content);*/
            //$html_content = $link;

            // get plain email content
            /*$plain_text = file_get_contents('emails/email_verification.txt');
            $plain_text = preg_replace('/{link}/', $link, $plain_text);*/
            $plain_text = $html_content;


            $smtp_server = 'smtp.mailtrap.io';
            $username = 'f67054347185ac';
            $password = 'eebd296edd6a0f';
            $port = '465';


            $message = (new \Swift_Message('Wonderful Subject'))
                ->setSubject("PWBox")
                ->setFrom(['user@yourdomain.com' => 'iTech Empires'])
                ->setTo(["cristian@cristiangonzalez.com" => "Cristian"])
                ->setBody($html_content, 'text/html')// add html content
                ->addPart($plain_text, 'text/plain'); // Add plain text

            // Create the Transport
            $transport = (new \Swift_SmtpTransport($smtp_server, $port))
                ->setUsername($username)
                ->setPassword($password);

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            $mailer->send($message);


            return $response->withStatus(302)->withHeader('Location', '/profile');
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'profile.twig', ['error' => 'code: '.$e->getMessage()]);
        }
        return $response;
    }
}
