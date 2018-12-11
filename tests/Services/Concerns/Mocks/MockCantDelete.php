<?php

namespace Tests\Services\Concerns\Mocks;

class MockCantDelete extends MockHasCrudOperations
{
    protected $permittedActions = [
        'delete'    => false,
    ];
}