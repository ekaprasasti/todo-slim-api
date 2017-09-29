<?php

$config = require __DIR__ . '/../../config.php';

$dbHost = $config['settings.db.host'];
$dbPort = $config['settings.db.port'];
$dbUser = $config['settings.db.user'];
$dbPassword = $config['settings.db.password'];
$dbName = $config['settings.db.database'];

return [
    'propel' => [
        'database' => [
            'connections' => [
                'freedom' => [
                    'adapter'    => 'mysql',
                    'classname'  => 'Propel\Runtime\Connection\ConnectionWrapper',
                    'dsn'        => 'mysql:host='.$dbHost.';port='.$dbPort.';dbname='.$dbName,
                    'user'       => $dbUser,
                    'password'   => $dbPassword,
                    'attributes' => []
                ]
            ]
        ],
        'runtime' => [
            'defaultConnection' => 'freedom',
            'connections' => ['freedom']
        ],
        'generator' => [
            'defaultConnection' => 'freedom',
            'connections' => ['freedom']
        ]
    ]
];