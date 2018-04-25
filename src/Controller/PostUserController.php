<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');
            $service($data);
            $this->container->get('flash')->addMessage('register', 'User registered.');





            // create account verification link
            $link = 'http://' . $_SERVER['SERVER_NAME'] . '/activation.php?key=' . 'EXAMPLE';

            // get the html email content
            /*$html_content = file_get_contents('emails/email_verification.html');
            $html_content = preg_replace('/{link}/', $link, $html_content);*/
            $html_content = $link;

            // get plain email content
            /*$plain_text = file_get_contents('emails/email_verification.txt');
            $plain_text = preg_replace('/{link}/', $link, $plain_text);*/
            $plain_text = $link;


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
                ->setPassword($password)
            ;

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            $mailer->send($message);










            return $response->withStatus(302)->withHeader('Location', '/dashboard');
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'register.twig', ['error' => $e->getMessage()]);
        }
        return $response;
    }


}