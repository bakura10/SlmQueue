<?php

namespace Slm\Queue\Queue\Feature;

use Slm\Queue\Job\JobInterface;

/**
 * Contract for any queue that support batch push and batch deletion
 */
interface BatchableInterface
{
    /**
     * Push several jobs at once
     *
     * @param  JobInterface[] $jobs
     * @param  array          $options
     * @return void
     */
    public function batchPush(array $jobs, array $options = array());

    /**
     * Delete several jobs at once
     *
     * @param  JobInterface[] $jobs
     * @return void
     */
    public function batchDelete(array $jobs);
}
