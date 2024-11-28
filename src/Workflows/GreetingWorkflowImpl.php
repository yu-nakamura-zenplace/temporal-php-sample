<?php

namespace Temporal\Samples\Workflows;

use Temporal\Activity\ActivityOptions;
use Temporal\Workflow;
use Temporal\Samples\Activities\GreetingActivity;

class GreetingWorkflowImpl implements GreetingWorkflow
{
    private $activities;

    public function __construct()
    {
        $this->activities = Workflow::newActivityStub(
            GreetingActivity::class,
            ActivityOptions::new()->withStartToCloseTimeout('1 minute')
        );
    }

    public function greet(string $name)
    {
        return $this->activities->greet($name);
    }
}