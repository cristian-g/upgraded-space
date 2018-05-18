<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteUserController
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

    public function deleteAction(Request $request, Response $response)
    {
        try{
            $userid = $_SESSION['user_id'];
            $user = $this->container->get('get_user_use_case')($userid);
            $service = $this->container->get('delete_user_use_case');
            $service($userid);
            $_SESSION['user_id'] = null;

            //we delete the physical folder with all file on it
            $dir = __DIR__.'/../../public/uploads/'.$user->getUuid();
            $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new \RecursiveIteratorIterator($it,
                \RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($dir);

        } catch (\Exception $e){
            return $this->container->get('view')
                ->render($response, 'profile.twig', ['error' => 'Error inesperado.']);
        }

        $this->container->get('flash')->addMessage('landing', 'Hemos eliminado tu cuenta y todos tus datos correctamente. ¡Te echaremos de menos!');
        return $response->withStatus(302)->withHeader('Location', '/');
    }
}