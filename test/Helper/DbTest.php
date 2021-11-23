<?php

namespace HyperfTest\Helper;

use App\Helper\Db;
use App\Inquiry\Helper\Sql;
use HyperfTest\CliCase;

/**
 * @example composer test2 test/Helper/DbTest.php
 * @group ready
 */
class DbTest extends CliCase
{

    public function testA()
    {
        Sql::end();
        Sql::start();
        $is  = Db::tableExits('master', 'player');
        $is  = Db::tableExits('master', 'player');
        $is  = Db::tableExits('master', 'player');
        $sql = Sql::end();
        $this->assertEquals(1, count($sql));

    }

}