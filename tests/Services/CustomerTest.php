<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Resources\CustomerResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Responses\PagedResponse;
use DarkGhostHunter\FlowSdk\Services\Customer;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class CustomerTest extends TestCase
{
    /** @var Customer */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    protected function setUp()
    {
        $this->service = new Customer($flow = \Mockery::instanceMock(Flow::class));

        $flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testReverseCharge()
    {
        $this->adapter->expects('post')->andReturn([
            'status' => '1',
            'message' => 'Reverse charge was successful',
        ]);

        $response = $this->service->reverseCharge('commerceTrxId', 'chargeId');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals('1', $response->status);
    }

    public function testGetCard()
    {
        $this->adapter->expects('get')->andReturn([
            'status' => '1',
            'customerId' => 'cus_onoolldvec',
            'creditCardType' => 'Visa',
            'last4CardDigits' => '0366',
        ]);

        $resource = $this->service->getCard('token');

        $this->assertInstanceOf(CustomerResource::class, $resource);
        $this->assertEquals('1', $resource->status);
    }

    public function testRegisterCard()
    {
        $this->adapter->expects('post')->andReturn([
            'url' => $url = 'https://www.flow.cl/app/webpay/disclaimer.php',
            'token' => $token = '41097C28B5BD78C77F589FE4BC59E18AC333F9EU',
        ]);

        $response = $this->service->registerCard('customerId', 'urlReturn');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals(
            $url . '?token=' . $token,
            $response->getUrl()
        );
    }

    public function testUnregisterCard()
    {
        $this->adapter->expects('post')->andReturn([
            'foo' => 'bar',
        ]);

        $resource = $this->service->unregisterCard('customerId');

        $this->assertInstanceOf(CustomerResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testGetChargesPage()
    {
        $this->adapter->expects('get')->andReturn([
            'total' => 200,
            'hasMore' => 1,
            'data' => [
                ['item' => 1], ['item' => 2],
                ['item' => 3], ['item' => 4]
            ]
        ]);

        $resource = $this->service->getChargesPage('customerId', 1, [
            'filter' => 'filtered',
            'fromDate' => '1990-01-01',
            'status' => 'status',
        ]);

        $this->assertInstanceOf(PagedResponse::class, $resource);
        $this->assertIsArray($resource->items);
        $this->assertCount(4, $resource->items);
        $this->assertInstanceOf(BasicResource::class, $resource->items[0]);
        $this->assertInstanceOf(BasicResource::class, $resource->items[1]);
        $this->assertInstanceOf(BasicResource::class, $resource->items[2]);
        $this->assertInstanceOf(BasicResource::class, $resource->items[3]);
    }

    public function testCharge()
    {
        $this->adapter->expects('post')->andReturn([
            'foo' => 'bar'
        ]);

        $resource = $this->service->createCharge([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
