<?php

namespace Tests\Services\Concerns\Mocks;

class MockCantCommit extends MockHasCrudOperations
{
    protected $permittedActions = [
        'commit' => false
    ];
}