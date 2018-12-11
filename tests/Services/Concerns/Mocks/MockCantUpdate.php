<?php

namespace Tests\Services\Concerns\Mocks;

class MockCantUpdate extends MockHasCrudOperations
{
    protected $permittedActions = [
        'update'    => false,
    ];
}