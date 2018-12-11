<?php

namespace Tests\Helpers;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class MockFluentHiddenAttributes extends Fluent
{
    protected $hidden = ['hidden'];
}