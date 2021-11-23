<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
$re     = [
    'default' => [
        'driver'    => env('DB_BI_DRIVER', 'mysql'),
        'host'      => env('DB_BI_HOST', 'localhost'),
        'port'      => env('DB_BI_PORT', 3306),
        'database'  => env('DB_BI_DATABASE', 'hyperf'),
        'username'  => env('DB_BI_USERNAME', 'root'),
        'password'  => env('DB_BI_PASSWORD', ''),
        'charset'   => env('DB_BI_CHARSET', 'utf8mb4'),
        'collation' => env('DB_BI_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix'    => env('DB_BI_PREFIX', ''),
        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout'    => 3.0,
            'heartbeat'       => -1,
            'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache'     => [
            'handler'         => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key'       => '{mc:%s:m:%s}:%s:%s',
            'prefix'          => 'default',
            'ttl'             => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script'     => true,
        ],
        'commands'  => [
            'gen:model' => [
                'path'          => 'app/Model',
                'force_casts'   => true,
                'inheritance'   => 'Model',
                'uses'          => '',
                'table_mapping' => [],
            ],
        ],
    ],
    'weblog'  => [
        'driver'    => env('DB_WEBLOG_DRIVER', 'mysql'),
        'read'      => [
            'host' => [ env('DB_WEBLOG_HOST', 'localhost') ],
        ],
        'write'     => [
            'host' => [ '192.168.1.236' ],
        ],
        'port'      => env('DB_WEBLOG_PORT', 3306),
        'database'  => env('DB_WEBLOG_DATABASE', 'hyperf'),
        'username'  => env('DB_WEBLOG_USERNAME', 'root'),
        'password'  => env('DB_WEBLOG_PASSWORD', ''),
        'charset'   => env('DB_WEBLOG_CHARSET', 'utf8mb4'),
        'collation' => env('DB_WEBLOG_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix'    => env('DB_WEBLOG_PREFIX', ''),
        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout'    => 3.0,
            'heartbeat'       => -1,
            'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache'     => [
            'handler'         => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key'       => '{mc:%s:m:%s}:%s:%s',
            'prefix'          => 'default',
            'ttl'             => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script'     => true,
        ],
        'commands'  => [
            'gen:model' => [
                'path'          => 'app/Model/Weblog',
                'force_casts'   => true,
                'inheritance'   => 'Model',
                'uses'          => '',
                'table_mapping' => [],
            ],
        ],
    ],
    'logclient'  => [
        'driver'    => env('DB_LOGCLIENT_DRIVER', 'mysql'),
        'read'      => [
            'host' => [ env('DB_LOGCLIENT_HOST', 'localhost') ],
        ],
        'write'     => [
            'host' => [ 'a.com' ],
        ],
        'port'      => env('DB_LOGCLIENT_PORT', 3306),
        'database'  => env('DB_LOGCLIENT_DATABASE', 'hyperf'),
        'username'  => env('DB_LOGCLIENT_USERNAME', 'root'),
        'password'  => env('DB_LOGCLIENT_PASSWORD', ''),
        'charset'   => env('DB_LOGCLIENT_CHARSET', 'utf8mb4'),
        'collation' => env('DB_LOGCLIENT_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix'    => env('DB_LOGCLIENT_PREFIX', ''),
        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout'    => 3.0,
            'heartbeat'       => -1,
            'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache'     => [
            'handler'         => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key'       => '{mc:%s:m:%s}:%s:%s',
            'prefix'          => 'default',
            'ttl'             => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script'     => true,
        ],
        'commands'  => [
            'gen:model' => [
                'path'          => 'app/Model/Logclient',
                'force_casts'   => true,
                'inheritance'   => 'Model',
                'uses'          => '',
                'table_mapping' => [],
            ],
        ],
    ],
    'master'  => [
        'driver'    => env('DB_MASTER_DRIVER', 'mysql'),
        'read'      => [
            'host' => [ env('DB_MASTER_HOST', 'localhost') ],
        ],
        'write'     => [
            'host' => [ '192.168.1.236' ],
        ],
        'port'      => env('DB_MASTER_PORT', 3306),
        'database'  => env('DB_MASTER_DATABASE', 'hyperf'),
        'username'  => env('DB_MASTER_USERNAME', 'root'),
        'password'  => env('DB_MASTER_PASSWORD', ''),
        'charset'   => env('DB_MASTER_CHARSET', 'utf8mb4'),
        'collation' => env('DB_MASTER_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix'    => env('DB_MASTER_PREFIX', ''),
        'pool'      => [
            'min_connections' => 1,
            'max_connections' => 10,
            'connect_timeout' => 10.0,
            'wait_timeout'    => 3.0,
            'heartbeat'       => -1,
            'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
        ],
        'cache'     => [
            'handler'         => Hyperf\ModelCache\Handler\RedisHandler::class,
            'cache_key'       => '{mc:%s:m:%s}:%s:%s',
            'prefix'          => 'default',
            'ttl'             => 3600 * 24,
            'empty_model_ttl' => 600,
            'load_script'     => true,
        ],
        'commands'  => [
            'gen:model' => [
                'path'          => 'app/Model',
                'force_casts'   => true,
                'inheritance'   => 'Model',
                'uses'          => '',
                'table_mapping' => [],
            ],
        ],
    ],

];
$number = env('DB_PLAYERP_NUMBER', 1);
for ($i = 0; $i < $number; $i++) {

    $index = 'player' . $i;

    $player      =
        [
            'driver'    => env('DB_PLAYER_DRIVER' . $i, 'mysql'),
            'read'      => [
                'host' => [ env('DB_PLAYER_HOST_' . $i, 'localhost') ],
            ],
            'write'     => [
                'host' => [  'a.com' ],
            ],
            'port'      => env('DB_PLAYER_PORT_' . $i, 3306),
            'database'  => env('DB_PLAYER_DATABASE_' . $i, 'hyperf'),
            'username'  => env('DB_PLAYER_USERNAME_' . $i, 'root'),
            'password'  => env('DB_PLAYER_PASSWORD_' . $i, ''),
            'charset'   => env('DB_PLAYER_CHARSET_' . $i, 'utf8mb4'),
            'collation' => env('DB_PLAYER_COLLATION_' . $i, 'utf8mb4_unicode_ci'),
            'prefix'    => env('DB_PLAYER_PREFIX_' . $i, ''),
            'pool'      => [
                'min_connections' => 1,
                'max_connections' => 100,
                'connect_timeout' => 100.0,
                'wait_timeout'    => 10.0,
                'heartbeat'       => -1,
                'max_idle_time'   => (float)env('DB_MAX_IDLE_TIME', 60),
            ]
        ];
    $re [$index] = $player;
}

return $re;