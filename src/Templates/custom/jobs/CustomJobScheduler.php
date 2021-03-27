<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\Sugarcrm\custom\jobs;

/**
 * Persistent scheduler which is responsible to create subsequent jobs based
 * on what needs to be consumed from the database queue.
 */
class CustomJobScheduler implements \RunnableSchedulerJob
{
    /**
     * @var \SchedulersJob
     */
    protected $job;

    /**
     * Ctor.
     */
    public function __construct()
    {
        $GLOBALS['log']->fatal('Custom Job Construct ');
    }

    /**
     * {@inheritdoc}
     */
    public function setJob(\SchedulersJob $job)
    {
        $this->job = $job;
    }

    /**
     * {@inheritdoc}
     */
    public function run($data)
    {
        $str = html_entity_decode($data);
        $data = json_decode($str, true);
        $GLOBALS['log']->fatal('Custom Job Run ');
        if (!$this->isWorkDay()) {
            //reschedule to the next day
            $this->reschedule($this->job->job_delay);

            return;
        }

        try {
            //do staff
        } catch (\Exception $e) {
            return $this->job->failJob($e->getMessage());
        }

        return $this->job->succeedJob('Success custom job');
    }

    /**
     * @return bool
     */
    protected function isWorkDay()
    {
        $day = date('w');

        return in_array($day, ['1', '2', '3', '4', '5']);
    }

    /**
     * @param int $nextStartSeconds
     */
    protected function reschedule($nextStartSeconds)
    {
        $nextExecuteTime = $GLOBALS['timedate']->getNow()->modify("+{$nextStartSeconds} seconds")->asDb();
        $this->job->execute_time = $nextExecuteTime;
        $this->job->status = 'queued';
        $this->job->save();
    }
}
