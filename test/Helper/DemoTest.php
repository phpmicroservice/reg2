<?php


namespace HyperfTest\Helper;


use App\Core\Logger;
use HyperfTest\CliCase;

/**
 * Class DemoTest
 *
 * @example composer test2 test/Helper/DemoTest.php
 * @package HyperfTest\Helper
 */
class DemoTest extends CliCase
{

    public function test1()
    {
        $filepath = Logger::file();
        $file     = new \SplFileObject($filepath);

        $file->seek(PHP_INT_MAX);

        $total_lines = $file->key();

        $file->seek($total_lines - 20);
        $s = [];
        while (!$file->eof()) {
            $line = $file->current();
            if (strlen($line) > 1000) {
                $s[] = substr($line, 0, 900);
            } else {
                $s[] = $line;
            }

            $file->next();
        }
        foreach (range(1, 10) as $u) {
            Logger::info(11);
        }
        dump($s);
//        $file->f
    }

}