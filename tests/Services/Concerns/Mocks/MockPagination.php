<?php

namespace Tests\Services\Concerns\Mocks;

use DarkGhostHunter\FlowSdk\Services\BaseService;
use DarkGhostHunter\FlowSdk\Services\Concerns\HasPagination;

class MockPagination extends BaseService
{
    use HasPagination;

}