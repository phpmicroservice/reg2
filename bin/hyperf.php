#!/usr/bin/env php
<?php


ini_set('display_startup_errors', 'on');

error_reporting(E_ALL);

!defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));
!defined('SWOOLE_HOOK_FLAGS') && define('SWOOLE_HOOK_FLAGS', SWOOLE_HOOK_ALL);
require BASE_PATH . '/app/function.php';

require BASE_PATH . '/vendor/autoload.php';

$GLOBALS['GAME_ROOT'] = BASE_PATH . '/server/';

define('GAME_ROOT', $GLOBALS['GAME_ROOT']);
define('RUN_UNIQID', uniqid());
define('START_TIME', time());
$path          = BASE_PATH . '/config/version';
$versionString = file_get_contents($path);
if (getenv('APP_ENV') == 'dev') {
    define('APP_VERSION', $versionString . '.' . (date('d') + date('H') + date('i')));
} else {
    define('APP_VERSION', $versionString);
}


// Self-called anonymous function that creates its own scope and keep the global namespace clean.
(function () {
    Hyperf\Di\ClassLoader::init();
    /** @var Psr\Container\ContainerInterface $container */
    $container = require BASE_PATH . '/config/container.php';

    $application = $container->get(Hyperf\Contract\ApplicationInterface::class);
    $application->run();
})();
