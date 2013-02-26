<?php

namespace Slm\Queue\Queue;

use Slm\Queue\Job\JobPluginManager;

/**
 * AbstractQueue
 */
abstract class AbstractQueue implements QueueInterface
{
    /**
     * @var JobPluginManager
     */
    protected $jobPluginManager;

    /**
     * @var string
     */
    protected $name;


    /**
     * @param string           $name
     * @param JobPluginManager $jobPluginManager
     */
    public function __construct($name, JobPluginManager $jobPluginManager)
    {
        $this->name             = $name;
        $this->jobPluginManager = $jobPluginManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getJobPluginManager()
    {
        return $this->jobPluginManager;
    }

    /**
     * Create a concrete instance of a job
     *
     * @param  string $className
     * @param  mixed  $content
     * @param  array  $metadata
     * @return \Slm\Queue\Job\JobInterface
     */
    protected function createJob($className, $content = null, array $metadata = array())
    {
        /** @var $job \Slm\Queue\Job\JobInterface */
        $job = $this->jobPluginManager->get($className);
        $job->setContent($content)
            ->setMetadata($metadata);

        return $job;
    }
}
