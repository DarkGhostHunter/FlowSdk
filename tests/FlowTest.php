<?php

namespace Tests;

use DarkGhostHunter\FlowSdk\Adapters\Processor;
use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Exceptions\Flow\InvalidUrlException;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use DarkGhostHunter\FlowSdk\Services\Coupon;
use DarkGhostHunter\FlowSdk\Services\Customer;
use DarkGhostHunter\FlowSdk\Services\Invoice;
use DarkGhostHunter\FlowSdk\Services\Payment;
use DarkGhostHunter\FlowSdk\Services\Plan;
use DarkGhostHunter\FlowSdk\Services\Refund;
use DarkGhostHunter\FlowSdk\Services\Settlement;
use DarkGhostHunter\FlowSdk\Services\Subscription;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class FlowTest extends TestCase
{

    /**
     * @var Flow
     */
    protected $flow;

    protected function setUp()
    {
        $this->flow = new Flow(new NullLogger());
    }

    public function testLogger()
    {
        $this->flow->setLogger(new NullLogger());

        $this->assertInstanceOf(LoggerInterface::class, $this->flow->getLogger());
    }

    public function testIsProduction()
    {
        $this->flow->isProduction(false);
        $this->assertFalse($this->flow->isProduction());

        $this->flow->isProduction(true);
        $this->assertTrue($this->flow->isProduction());
    }

    public function testGetEndpointForEnvironment()
    {
        $this->flow->isProduction(false);
        $this->assertEquals('https://flow.tuxpan.com/api', $this->flow->getEndpoint());

        $this->flow->isProduction(true);
        $this->assertEquals('https://www.flow.cl/api', $this->flow->getEndpoint());
    }

    public function testReturnUrls()
    {
        $this->flow->setReturnUrls($urls = [
            'payment.urlReturn'     => 'https://app.com/payment/return',
            'card.urlReturn'        => 'https://app.com/card/return',
        ]);

        $this->assertInternalType('array', $this->flow->getReturnUrls());
        $this->assertEquals($urls, $this->flow->getReturnUrls());
    }

    public function testSingleReturnUrl()
    {
        $this->flow->setReturnUrls($urls = [
            'payment.urlReturn'     => $return = 'https://app.com/payment/return',
            'card.urlReturn'        => 'https://app.com',
        ]);

        $this->assertEquals($return, $this->flow->getReturnUrls('payment.urlReturn'));
        $this->assertNull($this->flow->getReturnUrls('this.returns.null'));
    }

    public function testReturnUrlsWithBaseUrl()
    {

        $this->flow->setReturnUrls([
            'payment.urlReturn'     => 'https://app.com/payment/return',
            'card.urlReturn'        => 'https://app.com/card/return',
        ]);

        $urls = [
            'payment.urlReturn'     => 'https://app.com/payment/return',
            'card.urlReturn'        => 'https://app.com/card/return',
        ];

        $this->assertInternalType('array', $this->flow->getReturnUrls());
        $this->assertEquals($urls, $this->flow->getReturnUrls());
    }

    public function testSetWebhookUrls()
    {
        $this->flow->setWebhookUrls([
            'payment.created'       => 'https://finances.app.com/webhooks/payment-created',
            'payment.createdEmail'  => 'https://finances.app.com/webhooks/payment-email-created',
            'plan.subscribed'       => 'https://finances.app.com/webhooks/plan-subscribed',
            'refund.created'        => 'https://finances.app.com/webhooks/refund-created',
            'customer.registered'   => 'https://finances.app.com/webhooks/customer-registered',
        ]);

        $urls = [
            'payment.created'       => 'https://finances.app.com/webhooks/payment-created',
            'payment.createdEmail'  => 'https://finances.app.com/webhooks/payment-email-created',
            'plan.subscribed'       => 'https://finances.app.com/webhooks/plan-subscribed',
            'refund.created'        => 'https://finances.app.com/webhooks/refund-created',
            'customer.registered'   => 'https://finances.app.com/webhooks/customer-registered',
        ];

        $this->assertInternalType('array', $this->flow->getWebhookUrls());
        $this->assertEquals($urls, $this->flow->getWebhookUrls());
    }

    public function testSetWebhookUrlsWithBaseUrl()
    {
        $this->flow->setWebhookUrls($urls = [
            'payment.created'       => 'https://app.com/webhooks/payment-created',
            'payment.createdEmail'  => 'https://app.com/webhooks/payment-email-created',
            'plan.subscribed'       => 'https://finances.app.com/webhooks/plan-subscribed',
            'refund.created'        => 'https://app.com/webhooks/refund-created',
            'customer.registered'   => 'https://finances.app.com/webhooks/customer-registered',
        ]);

        $this->assertInternalType('array', $this->flow->getWebhookUrls());
        $this->assertEquals($urls, $this->flow->getWebhookUrls());
    }

    public function testReturnsNullWebhookIfNotSet()
    {
        $webhook = $this->flow->getWebhookUrls('payment.created');

        $this->assertNull($webhook);
    }

    public function testSetWebhookUrlsExceptionOnNoBaseUrl()
    {
        $this->expectException(\Exception::class);

        $this->flow->setWebhookUrls([
            'payment.created'       => 'webhooks/payment-created',
        ]);
    }

    public function testCredentials()
    {
        $this->flow->setCredentials($credentials = [
            'apiKey'    => '1F90971E-8276-4713-97FF-2BLF5091EE3B',
            'secret'    => 'f8b45f9b8bcdb5702dc86a1b894492303741c405',
        ]);

        $this->assertInstanceOf(Fluent::class, $this->flow->getCredentials());
        $this->assertEquals($credentials, $this->flow->getCredentials()->toArray());
    }

    public function testWebhookSecret()
    {
        $this->flow->setWebhookSecret($secret = bin2hex(random_bytes(16)));

        $this->assertEquals($secret, $this->flow->getWebhookSecret());
    }

    public function testMake()
    {
        $flow = Flow::make('production', $credentials = [
            'apiKey'    => '1F90971E-8276-4713-97FF-2BLF5091EE3B',
            'secret'    => 'f8b45f9b8bcdb5702dc86a1b894492303741c405',
        ]);

        $this->assertInstanceOf(Flow::class, $flow);

        $this->assertTrue($flow->isProduction());

        $flow = Flow::make('not-production', $credentials);

        $this->assertFalse($flow->isProduction());

        $this->assertInstanceOf(Fluent::class, $flow->getCredentials());
        $this->assertEquals($credentials, $flow->getCredentials()->toArray());
    }

    public function testMakeWithLogger()
    {
        $flow = Flow::make('production', $credentials = [
            'apiKey'    => '1F90971E-8276-4713-97FF-2BLF5091EE3B',
            'secret'    => 'f8b45f9b8bcdb5702dc86a1b894492303741c405',
        ], \Mockery::instanceMock(LoggerInterface::class));

        $this->assertInstanceOf(LoggerInterface::class, $this->flow->getLogger());
    }

    public function testMakeWithNullLogger()
    {
        $flow = Flow::make('production', $credentials = [
            'apiKey'    => '1F90971E-8276-4713-97FF-2BLF5091EE3B',
            'secret'    => 'f8b45f9b8bcdb5702dc86a1b894492303741c405',
        ]);

        $this->assertInstanceOf(NullLogger::class, $this->flow->getLogger());
    }

    public function testInvalidUrlException()
    {
        $this->expectException(InvalidUrlException::class);

        $this->flow->setWebhookUrls([
            'example' => 'an_invalid*URL'
        ]);
    }

    public function testAdapter()
    {
        $this->flow->setAdapter(\Mockery::instanceMock(AdapterInterface::class));

        $this->assertInstanceOf(AdapterInterface::class, $this->flow->getAdapter());
    }

    public function testInvalidService()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->flow->invalidService();
    }

    public function testProcessor()
    {
        $this->flow->setProcessor(\Mockery::instanceMock(Processor::class));

        $this->assertAttributeInstanceOf(Processor::class, 'processor', $this->flow);
    }

    public function testSendGetProduction()
    {

        $this->flow->isProduction(true);
        $this->flow->setAdapter($adapter = \Mockery::instanceMock(AdapterInterface::class));
        $this->flow->setProcessor($processor = \Mockery::instanceMock(Processor::class));

        $processor->expects('prepare')->with(
            'get', $array = ['foo' => 'bar']
        )->andReturn(
            $params = '?apiKey=apiKey&foo=bar&s=123456789'
        );

        $adapter->expects('get')
            ->with('https://www.flow.cl/api/endpoint/method' . $params)
            ->andReturn(['foo' => 'bar']);

        $response = $this->flow->send('get', '/endpoint/method/', ['foo' => 'bar']);

        $this->assertInternalType('array', $response);
        $this->assertEquals('bar', $response['foo']);

    }

    public function testSendGetSandbox()
    {
        $this->flow->isProduction(false);
        $this->flow->setAdapter($adapter = \Mockery::instanceMock(AdapterInterface::class));
        $this->flow->setProcessor($processor = \Mockery::instanceMock(Processor::class));

        $processor->expects('prepare')->with(
            'get', $array = ['foo' => 'bar']
        )->andReturn(
            $params = '?apiKey=apiKey&foo=bar&s=123456789'
        );

        $adapter->expects('get')
            ->with('https://flow.tuxpan.com/api/endpoint/method' . $params)
            ->andReturn(['foo' => 'bar']);

        $response = $this->flow->send('get', '/endpoint/method/', ['foo' => 'bar']);

        $this->assertInternalType('array', $response);
        $this->assertEquals('bar', $response['foo']);

    }

    public function testSendPostSandbox()
    {
        $this->flow->setAdapter($adapter = \Mockery::instanceMock(AdapterInterface::class));
        $this->flow->setProcessor($processor = \Mockery::instanceMock(Processor::class));

        $processor->expects('prepare')->with(
            'post', $array = ['foo' => 'bar']
        )->andReturn(
            $params = [
                'apiKey' => 'apiKey',
                'foo' => 'bar',
                's' => '123456789'
            ]
        );

        $adapter->expects('post')
            ->with('https://flow.tuxpan.com/api/endpoint/method', $params)
            ->andReturn(['foo' => 'bar']);

        $response = $this->flow->send('post', '/endpoint/method/', ['foo' => 'bar']);

        $this->assertInternalType('array', $response);
        $this->assertEquals('bar', $response['foo']);
    }

    public function testSendPostProduction()
    {
        $this->flow->isProduction(true);
        $this->flow->setAdapter($adapter = \Mockery::instanceMock(AdapterInterface::class));
        $this->flow->setProcessor($processor = \Mockery::instanceMock(Processor::class));

        $processor->expects('prepare')->with(
            'post', $array = ['foo' => 'bar']
        )->andReturn(
            $params = [
                'apiKey' => 'apiKey',
                'foo' => 'bar',
                's' => '123456789'
            ]
        );

        $adapter->expects('post')
            ->with('https://www.flow.cl/api/endpoint/method', $params)
            ->andReturn(['foo' => 'bar']);

        $response = $this->flow->send('post', '/endpoint/method/', ['foo' => 'bar']);

        $this->assertInternalType('array', $response);
        $this->assertEquals('bar', $response['foo']);
    }

    public function testSettlement()
    {
        $this->assertInstanceOf(Settlement::class, $this->flow->settlement());
    }

    public function testRefund()
    {
        $this->assertInstanceOf(Refund::class, $this->flow->refund());
    }

    public function testCustomer()
    {
        $this->assertInstanceOf(Customer::class, $this->flow->customer());
    }

    public function testPlan()
    {
        $this->assertInstanceOf(Plan::class, $this->flow->plan());
    }

    public function testInvoice()
    {
        $this->assertInstanceOf(Invoice::class, $this->flow->invoice());
    }

    public function testCoupon()
    {
        $this->assertInstanceOf(Coupon::class, $this->flow->coupon());
    }

    public function testSubscription()
    {
        $this->assertInstanceOf(Subscription::class, $this->flow->subscription());
    }

    public function testPayment()
    {
        $this->assertInstanceOf(Payment::class, $this->flow->payment());
    }
}
