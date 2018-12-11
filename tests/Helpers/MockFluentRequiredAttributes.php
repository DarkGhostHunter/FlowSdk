<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentRequiredAttributes extends Fluent
{
    protected $required = [
        'foo'
    ];
}