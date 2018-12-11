<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentAttribute extends Fluent
{
    public function getKeyAttribute()
    {
        return 'value';
    }
    public function getNotFooAttribute()
    {
        return 'lol';
    }
}