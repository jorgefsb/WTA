<?php

if ( !defined('__DIR__') ) define('__DIR__', dirname(__FILE__)); 

// Define path to application directory
ini_set('session.auto_start', 0);
error_reporting(E_ALL);
 ini_set("display_errors", 1);
date_default_timezone_set('America/Lima');
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));


defined('APPLICATION_PUBLIC')
    || define('APPLICATION_PUBLIC', realpath(__DIR__ . '/'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
        (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR,
        array(
        realpath(APPLICATION_PATH . '/../library'),
        get_include_path(),
    )));


/** Zend_Application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
