<?php

namespace Slm\Queue\Worker;

use Slm\Queue\Job\JobInterface;
use Slm\Queue\Options\WorkerOptions;
use Slm\Queue\Queue\QueueInterface;
use Slm\Queue\Queue\QueuePluginManager;

/**
 * AbstractWorker
 */
abstract class AbstractWorker implements WorkerInterface
{
    /**
     * @var QueuePluginManager
     */
    protected $queuePluginManager;

    /**
     * @var bool
     */
    protected $stopped = false;

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

        // Listen to the signals SIGTERM and SIGINT so that the worker can be killed properly
        declare(ticks = 1);
        pcntl_signal(SIGTERM, array($this, 'handleSignal'));
        pcntl_signal(SIGINT,  array($this, 'handleSignal'));
    }

    /**
     * {@inheritDoc}
     */
    public function processQueue($queueName, array $options = array())
    {
        /** @var $queue QueueInterface */
        $queue = $this->queuePluginManager->get($queueName);
        $count = 0;

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

                $this->processJob($job, $queue);
                $count++;

                // Those are various criterias to stop the queue processing
                if (
                    $count === $this->options->getMaxRuns()
                    || memory_get_usage() > $this->options->getMaxMemory()
                    || $this->isStopped()
                ) {
                    return $count;
                }
            }
        }

        return $count;
    }

    /**
     * Check if the script has been stopped from a signal
     *
     * @return bool
     */
    public function isStopped()
    {
        return $this->stopped;
    }

    /**
     * Handle the signal
     *
     * @param int $signo
     */
    protected function handleSignal($signo)
    {
        switch($signo) {
            case SIGTERM:
            case SIGINT:
                $this->stopped = true;
                break;
        }
    }
}
