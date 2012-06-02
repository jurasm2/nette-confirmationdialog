<?php

use Nette\Application\Routers\Route;


// Load Nette Framework
require __DIR__ . '/../libs/nette.min.php';


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->enableDebugger(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');
$container = $configurator->createContainer();
$container->router[] = new Nette\Application\Routers\SimpleRouter('Default:default');


// Configure and run the application!
$container->application->run();