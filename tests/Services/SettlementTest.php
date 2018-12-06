<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Services\Settlement;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SettlementTest extends TestCase
{

    /** @var Settlement */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    protected function setUp()
    {
        $this->service = new Settlement($flow = \Mockery::instanceMock(Flow::class));

        $flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testGetByDate()
    {
        $this->adapter->expects('get')->andReturn([
            'foo' => 'bar',
        ]);

        $resource = $this->service->getByDate('1990-01-01');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
