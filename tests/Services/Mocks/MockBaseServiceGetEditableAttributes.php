<?php

namespace Tests\Services\Mocks;

use DarkGhostHunter\FlowSdk\Services\BaseService;

class MockBaseServiceGetEditableAttributes extends BaseService
{
    protected $editableAttributes = ['foo', 'bar'];
}