<?php

namespace Tests\Resources;

use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Resources\CustomerResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Responses\PagedResponse;
use DarkGhostHunter\FlowSdk\Services\Customer;
use PHPUnit\Framework\TestCase;

class CustomerResourceTest extends TestCase
{
    /** @var CustomerResource */
    protected $resource;

    /** @var Customer|\Mockery\MockInterface */
    protected $mockService;

    protected $attributes = [
        'customerId' => 'theCustomerId',
        'foo' => 'bar',
    ];

    protected function setUp()
    {
        $this->resource = new CustomerResource($this->attributes);

        $this->mockService = \Mockery::instanceMock(Customer::class);

        $this->resource->setService($this->mockService);
        $this->resource->setExists(true);
    }

    public function testUnregisterCard()
    {
        $this->mockService->expects('getId')->andReturn('customerId');
        $this->mockService->expects('unregisterCard')
            ->with('theCustomerId')
            ->andReturn($this->resource);

        $resource = $this->resource->unregisterCard();

        $this->assertInstanceOf(CustomerResource::class, $resource);
        $this->assertEquals($this->attributes, $resource->toArray());
    }

    public function testNoUnregisterCardOnCustomerDoesntExists()
    {
        $this->mockService->expects('getId')->andReturn('customerId');
        $this->resource->setExists(false);

        $resource = $this->resource->unregisterCard();

        $this->assertFalse($resource);
    }

    public function testReverseCharge()
    {
        $this->mockService->expects('reverseCharge')
            ->with('commerceOrder', 'theCommerceOrder')
            ->andReturn(new BasicResponse($array = [
                'status' => '1',
                'message' => 'Reverse charge was successful',
            ]));

        $response = $this->resource->reverseCharge('commerceOrder', 'theCommerceOrder');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals($array, $response->toArray());
    }

    public function testNoReverseChargeOnCustomerDoesntExists()
    {
        $this->resource->setExists(false);

        $response = $this->resource->reverseCharge('commerceOrder', 'theCommerceOrder');

        $this->assertFalse($response);
    }

    public function testGetChargesPage()
    {
        $this->mockService->expects('getId')->andReturn('customerId');
        $this->mockService->expects('getChargesPage')
            ->with('theCustomerId', 2, [
                'filter' => 'theFilter',
                'fromDate' => '1990-01-01',
                'status' => 'theStatus',
            ])
            ->andReturn(new PagedResponse([
                'total' => 200,
                'page' => 2,
                'hasMore' => 1,
                'items' => [
                    new BasicResource(['item' => 1]), new BasicResource(['item' => 2]),
                    new BasicResource(['item' => 3]), new BasicResource(['item' => 4]),
                ],
            ]));

        $response = $this->resource->getChargesPage(2, [
            'filter' => 'theFilter',
            'fromDate' => '1990-01-01',
            'status' => 'theStatus',
        ]);

        $this->assertInstanceOf(PagedResponse::class, $response);
        $this->assertIsArray($response->items);
        $this->assertInstanceOf(BasicResource::class, $response->items[0]);
        $this->assertInstanceOf(BasicResource::class, $response->items[1]);
        $this->assertInstanceOf(BasicResource::class, $response->items[2]);
        $this->assertInstanceOf(BasicResource::class, $response->items[3]);
    }

    public function testNoGetChargesPageOnCustomerNotExisting()
    {
        $this->mockService->expects('getId')->andReturn('customerId');
        $this->resource->setExists(false);

        $response = $this->resource->getChargesPage(2, [
            'filter' => 'theFilter',
            'fromDate' => '1990-01-01',
            'status' => 'theStatus',
        ]);

        $this->assertFalse($response);
    }

    public function testCharge()
    {
        $this->mockService->expects('getId')->andReturn('customerId');

        $this->mockService->expects('charge')
            ->with(['foo' => 'bar', 'customerId' => 'theCustomerId'])
            ->andReturn(new CustomerResource(['foo' => 'bar']));

        $this->resource->creditCardType = 'visa';
        $this->resource->last4CardDigits = 1234;

        $resource = $this->resource->charge([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(CustomerResource::class, $resource);
    }

    public function testNoChargeOnNoCreditCard()
    {
        $this->mockService->expects('getId')->andReturn('customerId');

        $this->mockService->expects('charge')
            ->with(['foo' => 'bar', 'customerId' => 'theCustomerId'])
            ->andReturn(new CustomerResource(['foo' => 'bar']));

        $resource = $this->resource->charge([
            'foo' => 'bar'
        ]);

        $this->assertFalse($resource);
    }

    public function testRegisterCard()
    {
        $this->mockService->expects('getId')->andReturn('customerId');

        $this->mockService->expects('registerCard')
            ->with('theCustomerId', 'http://app.com/return')
            ->andReturn(new BasicResponse([
                'url' => $url = 'https://www.flow.cl/app/webpay/disclaimer.php',
                'token' => $token = '41097C28B5BD78C77F589FE4BC59E18AC333F9EU',
            ]));

        $response = $this->resource->registerCard('http://app.com/return');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals("$url?token=$token", $response->getUrl());
    }
}
