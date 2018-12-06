<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Services\Payment;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PaymentTest extends TestCase
{

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    /** @var Payment */
    protected $service;

    /** @var AdapterInterface|\Mockery\MockInterface */
    protected $adapter;

    protected function setUp()
    {
        $this->service = new Payment($this->flow = \Mockery::instanceMock(Flow::class));

        $this->flow->expects('getAdapter')->andReturn($this->adapter = \Mockery::instanceMock(AdapterInterface::class));

        $this->flow->expects('getLogger')->andReturn($logger = \Mockery::instanceMock(LoggerInterface::class));

        $logger->expects('debug');
    }

    public function testPaymentHasDefaultReturnAndWebhookUrls()
    {
        $this->flow->expects('getWebhookWithSecret')
            ->with('payment.urlConfirmation')
            ->andReturn(
                $webhook = 'http://myapp.com/payment/webhook?secret=d6199909d0b5fdc22c9db625e4edf0d6'
            );

        $this->flow->expects('getReturnUrls')
            ->with('payment.urlReturn')
            ->andReturn(
                $return = 'http://myapp.com/payment/return'
            );

        $resource = $this->service->make([]);

        $this->assertEquals($webhook, $resource->urlConfirmation);
        $this->assertEquals($return, $resource->urlReturn);
    }

    public function testPaymentDoesNotHaveDefaultReturnAndWebhookUrls()
    {
        $this->flow->expects('getWebhookWithSecret')
            ->with('payment.urlConfirmation')
            ->andReturnNull();

        $this->flow->expects('getReturnUrls')
            ->with('payment.urlReturn')
            ->andReturnNull();

        $resource = $this->service->make([]);

        $this->assertNull($resource->urlConfirmation);
        $this->assertNull($resource->urlReturn);
    }

    public function testCreateByEmail()
    {
        $this->adapter->expects('post')->andReturn([
            'url' => 'https://api.flow.cl/flow',
            'token' => '33373581FC32576FAF33C46FC6454B1FFEBD7E1H',
        ]);

        $response = $this->service->createByEmail([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals('https://api.flow.cl/flow', $response->url);
        $this->assertEquals('33373581FC32576FAF33C46FC6454B1FFEBD7E1H', $response->token);
        $this->assertEquals(
            'https://api.flow.cl/flow?token=33373581FC32576FAF33C46FC6454B1FFEBD7E1H',
            $response->getUrl()
        );
    }

    public function testGetByCommerceOrder()
    {
        $this->adapter->expects('post')->andReturn([
            'url' => 'https://api.flow.cl/flow',
            'token' => '33373581FC32576FAF33C46FC6454B1FFEBD7E1H',
        ]);

        $response = $this->service->createByEmail([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals('https://api.flow.cl/flow', $response->url);
        $this->assertEquals('33373581FC32576FAF33C46FC6454B1FFEBD7E1H', $response->token);
        $this->assertEquals(
            'https://api.flow.cl/flow?token=33373581FC32576FAF33C46FC6454B1FFEBD7E1H',
            $response->getUrl()
        );
    }

    public function testGetByCommerceId()
    {
        $this->adapter->expects('get')->andReturn([
            'foo' => 'bar',
        ]);

        $this->flow->expects('getWebhookWithSecret')
            ->andReturn(
                'http://app.com/webhook?secret=d6199909d0b5fdc22c9db625e4edf0d6'
            );

        $this->flow->expects('getReturnUrls')
            ->andReturn(
                'http://app.com/return'
            );

        $resource = $this->service->getByCommerceOrder('sf12377');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);

        $resource = $this->service->getByCommerceId('sf12377');

        $this->assertInstanceOf(BasicResource::class, $resource);
        $this->assertEquals('bar', $resource->foo);

    }
}
