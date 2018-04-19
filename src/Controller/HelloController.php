<?php

namespace Pwbox\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;

class HelloController
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
    public function __invoke(Request $request, Response $response, array $args)
    {
        if(isset($_SESSION['counter'])){
            $_SESSION['counter']+=1;
        }else{
            $_SESSION['counter']=1;
        }
        $name = $args['name'];
        $cookie = FigRequestCookies::get($request, 'advice', 0);
        if(empty($cookie->getValue())){
            $response = FigResponseCookies::set($response, SetCookie::create('advice')
                ->withValue(1)
                ->withDomain('slimapp.test')
                ->withPath('/')
            );
        }
        return $this->container->get('view')
            ->render($response, 'hello.twig', ['name' => $name, 'counter' => $_SESSION['counter'], 'advice' => $cookie->getValue()]);
    }
}
