<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Services\Plan;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PlanTest extends TestCase
{
    /** @var Plan */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Plan($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceHasDefaults()
    {
        $this->flow->expects('getWebhookWithSecret')
            ->with('plan.urlCallback')
            ->andReturn('https://app.com/plan/callback');

        $resource = $this->service->make([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
        $this->assertEquals('https://app.com/plan/callback', $resource->urlCallback);
    }

}
