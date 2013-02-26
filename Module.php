<?php

namespace Slm\Queue;

use Zend\Loader;
use Zend\ModuleManager\Feature;

/**
 * SlmQueue
 */
class Module implements Feature\ConfigProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
