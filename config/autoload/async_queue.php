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
return [
    'default' => [
        'driver'         => Hyperf\AsyncQueue\Driver\RedisDriver::class,
        'channel'        => 'queue',
        'timeout'        => 2,
        'retry_seconds'  => [ 5, 50, 100, 200, 200, 200 ],
        'handle_timeout' => 1000,
        'processes'      => 6,
        'concurrent'     => [
            'limit' => 6,
        ],
        'max_messages'   => 3
    ],
];
