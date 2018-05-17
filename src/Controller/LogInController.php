<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 5/2/18
 * Time: 11:19 AM
 */

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class LogInController
{
    /** @var ContainerInterface */
    private $container;

    /**
     * LogInController constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexAction(Request $request, Response $response){
        return $this->container->get('view')
            ->render($response, 'login.twig');
    }

    public function LogInAction(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();

            //password
            if (!(strlen($data['password']) > 5 and strlen($data['password']) < 13 and
                preg_match('/[A-Z]/', $data['password'])
                and preg_match('/[0-9]/', $data['password']))) {

                return $this->container->get('view')
                    ->render($response, 'login.twig', ['error' => "Contraseña con formato incorrecto"]);
            }

            //we check if the user is using email or username
            if (strpos($data['loginId'], "@")){
                if(filter_var($data['loginId'], FILTER_VALIDATE_EMAIL)){
                    $service = $this->container->get('get_from_email_use_case');
                    $user = $service($data['loginId']);
                }
                else{
                    return $this->container->get('view')
                        ->render($response, 'login.twig', ['error' => "Correo con formato incorrecto"]);
                }
            }
            else{
                //username
                if (!(ctype_alnum($data['loginId']) and strlen($data['loginId']) > 0 and strlen($data['loginId']) < 21)) {
                    return $this->container->get('view')
                        ->render($response, 'login.twig', ['error' => "Nombre de usuario con formato incorrecto"]);
                }else{
                    $service = $this->container->get('get_from_username_use_case');
                    $user = $service($data['loginId']);
                }
            }

            //we check if the password matches
            if ( password_verify($data['password'],$user->getPassword())){
                $_SESSION['user_id'] = $user->getId();
                return $response->withStatus(302)->withHeader('Location', '/dashboard');
            }
            else{
                if ($user->getId() == null){
                    //user doesn't exist
                    return $this->container->get('view')
                        ->render($response, 'login.twig', ['error' => "El usuario no existe."]);
                }
                else{
                    //user exists but password is incorrect
                    return $this->container->get('view')
                        ->render($response, 'login.twig', ['error' => "Combinación usuario-contraseña incorecta."]);
                }
            }
        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'login.twig', ['error' => 'Error inesperado.']);
        }

    }
}