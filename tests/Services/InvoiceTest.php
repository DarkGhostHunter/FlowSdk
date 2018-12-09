<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\InvoiceResource;
use DarkGhostHunter\FlowSdk\Services\Invoice;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class InvoiceTest extends TestCase
{

    /** @var Invoice */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $mockAdapter;

    protected function setUp()
    {
        $this->service = new Invoice($flow = \Mockery::instanceMock(Flow::class));

        $flow->expects('getAdapter')->andReturn($this->mockAdapter = \Mockery::instanceMock(AdapterInterface::class));

        $flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->mockAdapter->expects('get')->andReturn([
            'attemped' => 1,
            'status' => 1
        ]);

        $resource = $this->service->get('1');

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->mockAdapter->expects('get')->andReturn([
            'attemped' => 0,
            'status' => 1
        ]);

        $resource = $this->service->get('1');

        $this->assertTrue($resource->exists());
    }

    public function testCancel()
    {
        $this->mockAdapter->expects('post')->andReturns([
            'foo' => 'bar'
        ]);

        $resource = $this->service->cancel('theInvoiceId');

        $this->assertInstanceOf(InvoiceResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);

    }
}
