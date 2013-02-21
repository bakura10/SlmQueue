<?php

namespace SlmQueue\Worker;

use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use Zend\EventManager\Event;

/**
 * WorkerEvent
 */
class WorkerEvent extends Event
{
    /**
     * Various events you can listen to
     */
    const EVENT_PROCESS_JOB_PRE  = 'processJob.pre';
    const EVENT_PROCESS_JOB_POST = 'processJob.post';

    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var JobInterface
     */
    protected $job;


    /**
     * @param  JobInterface $job
     * @return WorkerEvent
     */
    public function setJob(JobInterface $job)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param  QueueInterface $queue
     * @return WorkerEvent
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return QueueInterface
     */
    public function getQueue()
    {
        return $this->queue;
    }
}
