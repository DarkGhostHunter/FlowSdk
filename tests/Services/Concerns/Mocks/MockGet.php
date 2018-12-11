<?php

namespace Tests\Services\Concerns\Mocks;

class MockGet extends MockHasCrudOperations
{
    protected $permittedActions = [
        'get'    => false,
    ];
}