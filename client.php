<?php

require 'vendor/autoload.php';

use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use Temporal\Samples\Workflows\GreetingWorkflow;

// Connect to temporal service on docker host
$host = getenv("TEMPORAL_HOST") ?: "host.docker.internal";

$serviceClient = ServiceClient::create("$host:7233");
$client = new WorkflowClient(
    $serviceClient,
    new \Temporal\Client\ClientOptions(['namespace' => 'default'])
);

// Start a workflow
$workflow = $client->newWorkflowStub(
    GreetingWorkflow::class,
    WorkflowOptions::new()->withTaskQueue('greeting-task-queue')
);

$workflowId = sprintf('greeting-workflow-%s', uniqid());
$run = $client->start($workflow, 'Temporal PHP');

printf(
    "Started workflow %s. Result: %s\n",
    $workflowId,
    $run->getResult()
);
