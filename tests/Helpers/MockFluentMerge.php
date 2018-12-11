<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentMerge extends Fluent
{
    protected $merge = ['toMerge'];

    protected $toMerge = [
        'key' => 'value',
        'foo' => 'notBar',
    ];
}