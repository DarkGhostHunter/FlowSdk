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
    protected $flow;

    protected function setUp()
    {
        $this->service = new Settlement($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testGetByDate()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['date' => '1990-01-01'])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $resource = $this->service->getByDate('1990-01-01');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
