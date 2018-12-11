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

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Invoice($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['invoiceId' => '1'])
            ->andReturn([
                'attemped' => 1,
                'status' => 1
            ]);

        $resource = $this->service->get('1');

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['invoiceId' => '1'])
            ->andReturn([
                'attemped' => 0,
                'status' => 1
            ]);

        $resource = $this->service->get('1');

        $this->assertTrue($resource->exists());
    }

    public function testCancel()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['invoiceId' => 'theInvoiceId'])
            ->andReturn([
                'foo' => 'bar'
            ]);

        $resource = $this->service->cancel('theInvoiceId');

        $this->assertInstanceOf(InvoiceResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);

    }
}
