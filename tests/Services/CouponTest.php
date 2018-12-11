<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Services\Coupon;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CouponTest extends TestCase
{
    /** @var Coupon */
    protected $service;

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Coupon($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturn([
                'expires' => '2018-01-01',
                'status' => 0
            ]);

        $resource = $this->service->create([
            'foo' => 'bar'
        ]);

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturn([
                'expires' => null,
                'status' => 1
            ]);

        $resource = $this->service->create(['foo' => 'bar']);

        $this->assertTrue($resource->exists());
    }
}
