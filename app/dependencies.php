<?php

$container = $app->getContainer();

// Register twig component
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', [/*
        'cache' => __DIR__ . '/../var/cache/'
    */]);// TODO desactivar en la versió de producció (és a dir al entregar la pràctica en aquest cas) (diu que NO CAL FER-HO)
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};


$container['doctrine'] = function ($container) {
    $config = new \Doctrine\DBAL\Configuration();
    $connection = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'],
        $config
    );
    return $connection;
};

$container['user_repository'] = function ($container) {
    $repository = new \Pwbox\Model\Implementations\DoctrineUserRepository(
        $container->get('doctrine')
    );
    return $repository;
};

$container['post_user_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\PostUserUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};
