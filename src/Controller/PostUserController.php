<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 4/12/18
 * Time: 9:01 PM
 */

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

    public function __invoke(Request $request, Response $response)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');
            $service($data);

        } catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write($e->getMessage());
        }
        // TODO: Implement __invoke() method.
    }


}