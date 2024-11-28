<?php

namespace Temporal\Samples\Activities;

class GreetingActivityImpl implements GreetingActivity
{
    public function greet(string $name)
    {
        $val = sprintf(
            "Hello, %s! Current time is %s",
            $name,
            date('Y-m-d H:i:s')
        );
        return $val;
    }
}