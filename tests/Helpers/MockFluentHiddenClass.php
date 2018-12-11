<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentHiddenClass extends Fluent
{
    protected $hidden = ['hidden'];
}