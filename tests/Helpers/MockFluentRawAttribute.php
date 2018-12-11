<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentRawAttribute extends Fluent
{
    public function getFooAttribute()
    {
        return 'notBar';
    }
}