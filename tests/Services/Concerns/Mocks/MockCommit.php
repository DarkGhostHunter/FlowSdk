<?php

namespace Tests\Services\Concerns\Mocks;

class MockCommit extends MockHasCrudOperations
{
    protected $permittedActions = [
        'commit' => true
    ];
}