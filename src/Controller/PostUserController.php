<?php

namespace Pwbox\Controller;

use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
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


    public function registerAction(Request $request, Response $response)
    {
        try {


            $data = $request->getParsedBody();

            /*
            //password
            if (!(strlen($data['password']) > 5 and strlen($data['password']) < 13 and
                preg_match('/[a-z]/', $data['password']) and preg_match('/[A-Z]/', $data['password'])
                and preg_match('/[0-9]/', $data['password']))) {

                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "contraseña con formato incorrecto"]);
            }

            //confirm password
            if (strcmp($data['password'], $data['confirm_password']) != 0) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "las dos contrasenyas no son iguales"]);
            }

            //username
            if (!(ctype_alnum($data['username']) and strlen($data['username']) > 0 and strlen($data['username']) < 21)) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "nombre de usuario con formato incorrecto"]);
            }

            //email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->container->get('view')
                    ->render($response, 'register.twig', ['error' => "correo con formato incorrecto"]);
            }
            */

            $service = $this->container->get('post_user_use_case');
            $_SESSION["user_id"] = $service($data);


            $this->container->get('flash')->addMessage('dashboard', 'User registered.');


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