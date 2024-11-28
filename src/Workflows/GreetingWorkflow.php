<?php

namespace Temporal\Samples\Workflows;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface GreetingWorkflow
{
    #[WorkflowMethod]
    public function greet(string $name);
}