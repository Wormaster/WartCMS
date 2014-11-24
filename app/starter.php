<?php

    //Автолоадер классов
require_once app_patch  . 'autoloader.php';

require_once app_patch . 'Core' . DS . 'Registry.php';
require_once app_patch . DS . 'config.php';



spl_autoload_register(array('Autoloader', 'loadPackagesN'));

$router = new \Core\Router();
$stat = new \Generic\Statistics();
$router->start();
$stat->collect_data();