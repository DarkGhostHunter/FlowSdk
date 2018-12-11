<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentSetRawAttribute extends Fluent
{
    public function setKeyAttribute()
    {
        return 'noValue';
    }
    public function setFooAttribute()
    {
        return 'noBar';
    }
}