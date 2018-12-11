<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentGetRawAttribute extends Fluent
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