<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentRequiredOnly extends Fluent
{
    protected $required = [
        'foo'
    ];
    protected $restrained = true;

}