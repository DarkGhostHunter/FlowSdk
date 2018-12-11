<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Services\Refund;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class RefundTest extends TestCase
{
    /** @var Refund */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Refund($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceHasDefaults()
    {
        $this->flow->expects('getWebhookUrls')
            ->with('refund.urlCallBack')
            ->andReturn('https://app.com/refund/callback');

        $resource = $this->service->make([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
        $this->assertEquals('https://app.com/refund/callback', $resource->urlCallBack);
    }

    public function testResourceDoesntHaveDefaults()
    {
        $this->flow->expects('getWebhookUrls')
            ->with('refund.urlCallBack')
            ->andReturnNull();

        $resource = $this->service->make([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
        $this->assertNull($resource->urlCallBack);

    }
}
