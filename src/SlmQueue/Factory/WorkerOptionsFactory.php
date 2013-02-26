<?php

namespace Slm\Queue\Factory;

use Slm\Queue\Options\WorkerOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WorkerOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new WorkerOptions($config['slm_queue']['worker']);
    }
}
