<?php

namespace Tests\Resources;

use DarkGhostHunter\FlowSdk\Resources\InvoiceResource;
use DarkGhostHunter\FlowSdk\Services\Invoice;
use PHPUnit\Framework\TestCase;

class InvoiceResourceTest extends TestCase
{

    /** @var \Mockery\MockInterface|Invoice */
    protected $service;

    /** @var \Mockery\MockInterface|InvoiceResource */
    protected $resource;

    protected function setUp()
    {
        $this->service = \Mockery::instanceMock(Invoice::class);

        $this->resource = new InvoiceResource([]);

        $this->resource->setService($this->service);
    }

    public function testCancel()
    {
        $this->service->expects('getId')->andReturn('invoiceId');
        $this->service->expects('cancel')
            ->with('theInvoiceId')
            ->andReturn(new InvoiceResource([
                'foo' => 'bar'
            ]));

        $this->resource->invoiceId = 'theInvoiceId';

        $bool = $this->resource->cancel();

        $this->assertTrue($bool);
    }

    public function testDoesntCancelBecauseIsAlreadyPaidOrCancelled()
    {
        $this->service->expects('getId')->andReturn('invoiceId');

        $this->resource->status = 1;
        $this->resource->attemped = 1;

        $bool = $this->resource->cancel();

        $this->assertFalse($bool);
    }
}
