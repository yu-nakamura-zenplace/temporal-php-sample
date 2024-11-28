<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Temporal\WorkerFactory;
use Temporal\Samples\Workflows\GreetingWorkflowImpl;
use Temporal\Samples\Activities\GreetingActivityImpl;

ini_set('display_errors', 'stderr');

// factory initiates and runs task queue specific activity and workflow workers
$factory = WorkerFactory::create();

// Worker that listens on a Task Queue and hosts both workflow and activity implementations.
$worker = $factory->newWorker('greeting-task-queue');


// Workflows are stateful. So you need a type to create instances.
$worker->registerWorkflowTypes(GreetingWorkflowImpl::class);

// Activities are stateless and thread safe. So a shared instance is used.
$worker->registerActivity(GreetingActivityImpl::class);

// start primary loop
$factory->run();
