<?php

$container = $app->getContainer();

// Register twig component
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', [/*
        'cache' => __DIR__ . '/../var/cache/'
    */]);// TODO desactivar en la versió de producció (és a dir al entregar la pràctica en aquest cas) (diu que NO CAL FER-HO)
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    $view->addExtension(new Knlv\Slim\Views\TwigMessages(
        new Slim\Flash\Messages()
    ));

    // Globals per a la vista
    $view->getEnvironment()->addGlobal('session', $_SESSION);
    if (isset($_SESSION["user_id"])) {
        // Informació de l'usuari
        $view->getEnvironment()->addGlobal('user', ($container->get('get_user_use_case'))($_SESSION["user_id"]));

        // Espai utilitzat
        $rootFolderSize = ($container->get('get_root_folder_size_use_case'))($_SESSION["user_id"]);
        if ($rootFolderSize == null) $rootFolderSize = 0;
        $view->getEnvironment()->addGlobal('rootFolderSize', $rootFolderSize);
    }
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

$container['upload_repository'] = function ($container) {
    $repository = new \Pwbox\Model\Implementations\DoctrineUploadRepository(
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

$container['edit_user_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\EditUserUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['get_user_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetUserUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['get_from_email_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFromEmailUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['get_from_username_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFromUsernameUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['delete_user_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\DeleteUserUseCase(
        $container->get('user_repository')
    );
    return $useCase;
};

$container['post_folder_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\PostFolderUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['post_file_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\PostFileUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_file_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFileUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_uploads_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetUploadsUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_folder_by_uuid_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFolderByUuidUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_folder_by_id_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFolderByIdUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_folder_size_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetFolderSizeUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['get_root_folder_size_use_case'] = function($container) {
    $useCase = new \Pwbox\Model\UseCase\GetRootFolderSizeUseCase(
        $container->get('upload_repository')
    );
    return $useCase;
};

$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};
