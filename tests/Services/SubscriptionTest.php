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

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Subscription($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['subscriptionId' => '1'])
            ->andReturn([
                'cancel_at' => '2018-01-01',
                'status' => 4
            ]);

        $resource = $this->service->get('1');

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['subscriptionId' => '1'])
            ->andReturn([
                'cancel_at' => '2030-01-01'
            ]);

        $resource = $this->service->get('1');

        $this->assertTrue($resource->exists());
    }

    public function testRemoveCoupon()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['subscriptionId' => 'theSubscriptionId'])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $resource = $this->service->removeCoupon('theSubscriptionId');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testAddCoupon()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), [
                'subscriptionId' => 'theSubscriptionId',
                'couponId' => 'couponId'
            ])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $resource = $this->service->addCoupon('theSubscriptionId', 'couponId');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testCancel()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), [
                'subscriptionId' => 'theSubscriptionId',
                'at_period_end' => 1
            ])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $resource = $this->service->cancel('theSubscriptionId', true);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
