<?php

namespace Tests\Services\Concerns\Mocks;

use DarkGhostHunter\FlowSdk\Services\BaseService;
use DarkGhostHunter\FlowSdk\Services\Concerns\HasCrudOperations;

class MockHasCrudOperations extends BaseService
{
    use HasCrudOperations;
}