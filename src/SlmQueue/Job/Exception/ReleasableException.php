<?php

namespace Slm\Queue\Job\Exception;

use RuntimeException;

/**
 * ReleasableException
 */
class ReleasableException extends RuntimeException
{
    /**
     * @var array
     */
    protected $options;


    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
    }

    /**
     * Get the options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
