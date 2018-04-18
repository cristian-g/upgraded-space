<?php
return [
    'settings' => [
        'displayErrorDetails' => true,// TODO desactivar en la versió de producció (és a dir al entregar la pràctica en aquest cas)
        'database' => [
            'dbname' => 'PWBox',
            'user' => 'homestead',
            'password' => 'secret',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ]
    ]
];