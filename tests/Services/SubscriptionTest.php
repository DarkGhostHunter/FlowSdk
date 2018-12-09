<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Services\Subscription;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SubscriptionTest extends TestCase
{

    /** @var Subscription */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    protected function setUp()
    {
        $this->service = new Subscription($flow = \Mockery::instanceMock(Flow::class));

        $flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->adapter->expects('get')->andReturn([
            'cancel_at' => '2018-01-01'
        ]);

        $resource = $this->service->get('1');

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->adapter->expects('get')->andReturn([
            'cancel_at' => '2030-01-01'
        ]);

        $resource = $this->service->get('1');

        $this->assertTrue($resource->exists());
    }

    public function testRemoveCoupon()
    {
        $this->adapter->expects('post')->andReturn([
            'foo' => 'bar',
        ]);

        $resource = $this->service->removeCoupon('subscriptionId');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testAddCoupon()
    {
        $this->adapter->expects('post')->andReturn([
            'foo' => 'bar',
        ]);

        $resource = $this->service->addCoupon('subscriptionId', 'couponId');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testCancel()
    {
        $this->adapter->expects('post')->andReturn([
            'foo' => 'bar',
        ]);

        $resource = $this->service->cancel('subscriptionId', true);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
