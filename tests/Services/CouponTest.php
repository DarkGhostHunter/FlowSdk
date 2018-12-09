<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Services\Coupon;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CouponTest extends TestCase
{
    /** @var Coupon */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    protected function setUp()
    {
        $this->service = new Coupon($flow = \Mockery::instanceMock(Flow::class));

        $flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->adapter->expects('post')->andReturn([
            'expires' => '2018-01-01',
            'status' => 0
        ]);

        $resource = $this->service->create([]);

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->adapter->expects('post')->andReturn([
            'expires' => null,
            'status' => 1
        ]);

        $resource = $this->service->create([]);

        $this->assertTrue($resource->exists());
    }
}
