<?php

namespace Pwbox\Controller;

use Doctrine\DBAL\Driver\Mysqli\MysqliException;
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
                    ->render($response, 'register.twig', ['error' => "Las dos contrasenyas no son iguales"]);
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
            if(!checkdate($birthdate[1], $birthdate[2], $birthdate[0])){
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "Fecha de nacimiento con formato incorrecta"]);
            }

            $service = $this->container->get('post_user_use_case');
            $_SESSION["user_id"] = $service($data);

            $user = ($this->container->get('get_user_use_case'))($_SESSION["user_id"]);

            $directory = __DIR__.'/../../public/uploads/'.$user->getUuid();// És relatiu o absolut, però respecte el file system (la màquina)
            $directory_default = __DIR__.'/../../0.recursos/avatar.jpeg';

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $profile = $uploadedFiles['profile_image'];
            if ($profile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $profile);
                $this->container->get('flash')->addMessage('dashboard', 'User registered with profile image.');
            }else{
                if (copy('avatar.jpeg', 'profile_image.jpeg')){
                    $this->container->get('flash')->addMessage('dashboard', 'User registered with default image.');
                }
                $this->container->get('flash')->addMessage('dashboard', 'User registered without image.');
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

            //$mailer->send($message);


            return $response->withStatus(302)->withHeader('Location', '/dashboard');
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'register.twig', ['error' => 'code: '.$e->getMessage()]);
        }
        return $response;
    }


}