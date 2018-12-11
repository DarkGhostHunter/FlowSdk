<?php

namespace Tests\Services\Concerns\Mocks;

class MockCantCreate extends MockHasCrudOperations
{
    protected $permittedActions = [
        'create'    => false,
    ];
}