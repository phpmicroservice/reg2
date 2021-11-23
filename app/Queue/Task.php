<?php

namespace App\Queue;


use App\Task\CleanCache;
use Hyperf\Task\TaskExecutor;
use Hyperf\Utils\ApplicationContext;

/**
 * Description of Task
 * 异步队列任务
 *
 * @author dongasai
 * @deprecated 错误的定义
 *
 */
class Task extends \Hyperf\AsyncQueue\Job
{

    public $task;
    public $params;
    public $timeout = 120;

    public function __construct($params)
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function handle()
    {
        $this->taskPush($this->task, $this->params);

        return [];

    }

    private function taskPush($task, $params)
    {
        $container = ApplicationContext::getContainer();
        $exec      = $container->get(TaskExecutor::class);
        $result    = $exec->execute(new \Hyperf\Task\Task([ $task, 'handle' ], $params), $this->timeout);
        pr($result);
    }

}
