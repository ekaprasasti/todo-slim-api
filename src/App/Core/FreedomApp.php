<?php

namespace App\Core;

use DI\ContainerBuilder;

class FreedomApp extends \DI\Bridge\Slim\App
{
    function __construct()
    {
        parent::__construct();
        $this->setupRoute();
        $this->setupMiddleware();
        $this->setupDependencies();
        $this->setupDB();
    }

    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . '/../../config.php');
        $builder->addDefinitions(__DIR__ . '/../../dependencies.php');

        $callable = function($c) { return new \App\Core\ExceptionHandler; };
        $builder->addDefinitions(['errorHandler' => $callable]);
    }

    protected function setupRoute()
    {
        $this->loadFromModuleDirectory('routes.php');
    }

    protected function setupMiddleware()
    {
        $this->loadFromModuleDirectory('middleware.php');
    }

    protected function setupDependencies()
    {
        $this->loadFromModuleDirectory('dependencies.php');
    }

    protected function setupDB()
    {
        $appContainer = $this->getContainer();
        $dbHost = $appContainer->get('settings.db.host');
        $dbPort = $appContainer->get('settings.db.port');
        $dbUser = $appContainer->get('settings.db.user');
        $dbPassword = $appContainer->get('settings.db.password');
        $dbName = $appContainer->get('settings.db.database');
        $manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $manager->setConfiguration(array (
        'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
        'dsn' => 'mysql:host='.$dbHost.';port='.$dbPort.';dbname='.$dbName,
        'user' => $dbUser,
        'password' => $dbPassword,
        'attributes' =>
        array (
            'ATTR_EMULATE_PREPARES' => false,
            'ATTR_TIMEOUT' => 30,
        ),
        'model_paths' =>
        array (
            0 => 'src',
            1 => 'vendor',
        ),
        ));

        $manager->setName('freedom');

        $serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
        $serviceContainer->setAdapterClass('freedom', 'mysql');
        $serviceContainer->setConnectionManager('freedom', $manager);
        $serviceContainer->setDefaultDatasource('freedom');
    }

    private function loadFromModuleDirectory($filename)
    {
        $app = $this;
        $dir = __DIR__ . '/../Modules';
        $files = scandir($dir);
        foreach($files as $key => $value) {
            if($value == '.' || $value == '..') {
                continue;
            }

            $fullpath = $dir . '/' . $value;

            if(!is_dir($fullpath)) {
                continue;
            }

            if(!is_file($fullpath . '/' . $filename)) {
                continue;
            }

            require $fullpath . '/' . $filename;
        }
    }
}
