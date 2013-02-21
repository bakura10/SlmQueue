<?php

namespace SlmQueue\Worker;

use SlmQueue\Job\JobInterface;
use SlmQueue\Options\WorkerOptions;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Queue\QueuePluginManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * AbstractWorker
 */
abstract class AbstractWorker implements WorkerInterface, EventManagerAwareInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var QueuePluginManager
     */
    protected $queuePluginManager;

    /**
     * @var WorkerOptions
     */
    protected $options;


    /**
     * Constructor
     *
     * @param QueuePluginManager $queuePluginManager
     * @param WorkerOptions      $options
     */
    public function __construct(QueuePluginManager $queuePluginManager, WorkerOptions $options)
    {
        $this->queuePluginManager = $queuePluginManager;
        $this->options            = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function processQueue($queueName, array $options = array())
    {
        /** @var $queue QueueInterface */
        $queue        = $this->queuePluginManager->get($queueName);
        $eventManager = $this->getEventManager();
        $count        = 0;

        $workerEvent = new WorkerEvent();
        $workerEvent->setQueue($queue);

        while (true) {
            // Pop operations may return a list of jobs or a single job
            $jobs = $queue->pop($options);

            if (!is_array($jobs)) {
                $jobs = array($jobs);
            }

            foreach ($jobs as $job) {
                // The queue may return null, for instance if a timeout was set
                if (!$job instanceof JobInterface) {
                    return $count;
                }

                $workerEvent->setJob($job);

                /** @var $result \Zend\EventManager\ResponseCollection */
                $result = $eventManager->trigger(WorkerEvent::EVENT_PROCESS_JOB_PRE, $workerEvent);

                if ($result->last() === false) {
                    continue;
                }

                $this->processJob($job, $queue);
                $count++;

                $eventManager->trigger(WorkerEvent::EVENT_PROCESS_JOB_POST, $workerEvent);

                // Those are various criterias to stop the queue processing
                if ($count === $this->options->getMaxRuns() || memory_get_usage() > $this->options->getMaxMemory()) {
                    return $count;
                }
            }
        }

        return $count;
    }

    /**
     * {@inheritDoc}
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()
    {
        if ($this->eventManager === null) {
            $this->eventManager = new EventManager();
        }

        return $this->eventManager;
    }
}
