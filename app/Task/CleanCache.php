<?php


namespace App\Task;


use Hyperf\Utils\Coroutine;

class CleanCache
{

    public function handle($cid)
    {
        pr(__CLASS__);
        sleep(10);
        return [
            'worker.cid' => $cid,
            // task_enable_coroutine 为 false 时返回 -1，反之 返回对应的协程 ID
            'task.cid'   => Coroutine::id(),
        ];
    }

}