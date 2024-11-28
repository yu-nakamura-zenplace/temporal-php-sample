<?php

namespace Temporal\Samples\Activities;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface]
interface GreetingActivity
{
    #[ActivityMethod]
    public function greet(string $name);
}