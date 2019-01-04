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

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    protected function setUp()
    {
        $this->service = new Customer($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testResourceExistenceFalse()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), \Mockery::type('array'))
            ->andReturn([
                'status' => 3
            ]);

        $resource = $this->service->create([]);

        $this->assertFalse($resource->exists());
    }

    public function testResourceExistenceTrue()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturn([
                'status' => 1
            ]);

        $resource = $this->service->create([
            'foo' => 'bar'
        ]);

        $this->assertTrue($resource->exists());
    }

    public function testReverseCharge()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['commerceTrxId' => 'chargeId'])
            ->andReturn([
                'status' => '1',
                'message' => 'Reverse charge was successful',
            ]);

        $response = $this->service->reverseCharge('commerceTrxId', 'chargeId');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals('1', $response->status);
    }

    public function testGetCard()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), ['token' => 'token'])
            ->andReturn([
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
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), [
                'customerId' => 'customerId',
                'url_return' => 'urlReturn',
            ])
            ->andReturn([
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

    public function testRegisterCardWithDefaultUrl()
    {
        $this->flow->expects('getReturnUrls')
            ->with('card.url_return')
            ->andReturn('http://myapp.com/card/default-return-url');

        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), [
                'customerId' => 'customerId',
                'url_return' => 'http://myapp.com/card/default-return-url',
            ])
            ->andReturn([
                'url' => $url = 'https://www.flow.cl/app/webpay/disclaimer.php',
                'token' => $token = '41097C28B5BD78C77F589FE4BC59E18AC333F9EU',
            ]);

        $response = $this->service->registerCard('customerId');

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals(
            $url . '?token=' . $token,
            $response->getUrl()
        );
    }

    public function testUnregisterCard()
    {
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['customerId' => 'theCustomerId'])
            ->andReturn([
                'foo' => 'bar',
            ]);

        $resource = $this->service->unregisterCard('theCustomerId');

        $this->assertInstanceOf(CustomerResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testGetChargesPage()
    {
        $this->flow->expects('send')
            ->with('get', \Mockery::type('string'), [
                'start' => 0,
                'limit' => 10,
                'filter' => 'filtered',
                'fromDate' => '1990-01-01',
                'status' => 'status',
                'customerId' => 'customerId'
            ])
            ->andReturn([
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
        $this->flow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturn([
                'foo' => 'bar'
            ]);

        $resource = $this->service->createCharge([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }
}
