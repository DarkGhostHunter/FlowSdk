<?php

namespace Tests\Adapters;

use DarkGhostHunter\FlowSdk\Adapters\GuzzleAdapter;
use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Exceptions\Adapter\AdapterException;
use DarkGhostHunter\FlowSdk\Exceptions\Transactions\TransactionException;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GuzzleAdapterTest extends TestCase
{

    /**
     * @var Flow|\Mockery\MockInterface
     */
    protected $mockFlow;

    /**
     * @var GuzzleAdapter
     */
    protected $adapter;

    /**
     * @var Client|\Mockery\MockInterface
     */
    protected $mockClient;

    protected function setUp()
    {
        $this->mockFlow = \Mockery::instanceMock(Flow::class);

        $this->mockFlow->expects('getCredentials')->andReturn(new Fluent([
            'apiKey' => 'apiKey',
            'secret' => 'secret'
        ]));

        $this->mockClient = \Mockery::instanceMock(Client::class);

        $this->adapter = new GuzzleAdapter($this->mockFlow, []);
        $this->adapter->setClient($this->mockClient);
    }

    public function testGetsClient()
    {
        $this->assertInstanceOf(Client::class, $this->adapter->getClient());
    }

    public function testPost()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('post')->with(
            \Mockery::type('string'),
            \Mockery::type('array')
        )->andReturnUsing(function ($url, $params) {
            return new Response(
                200, [],
                json_encode(compact('url', 'params'))
            );
        });

        $response = $this->adapter->post('endpoint/method', ['key' => 'value']);

        $this->assertArrayHasKey('url', $response);
        $this->assertEquals('https://flow.cl/api/endpoint/method', $response['url']);
        $this->assertEquals([
            'apiKey' => 'apiKey',
            'key' => 'value',
            's' => '389d3443316752387879959da16d579ac80e6a2d5f7f4422c0d8a2d50dd07c74'
        ], $response['params']['form_params']);

    }

    public function testFailedPost()
    {
        $this->expectException(TransactionException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('post')
            ->andReturn(new Response(
                401, [],
                json_encode($array = ['code' => 200, 'message' => 'Error message'])
            ));

        $this->adapter->post('post', ['key' => 'value']);
    }

    public function testUnreachablePost()
    {
        $this->expectException(AdapterException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');
        $logger->expects('error');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('post')
            ->andThrowExceptions([
                new RequestException(
                    'Error Communicating with Server',
                    new Request('POST', json_encode(['foo' => 'bar']))
                )
            ]);

        $this->adapter->post('post', ['foo' => 'bar']);
    }

    public function testGet()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');
        $logger->expects('error');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('get')->with(
            \Mockery::type('string')
        )->andReturnUsing(function ($string) {
            return new Response(
                200, [],
                json_encode(['foo' => 'bar', 'url' => $string])
            );
        });

        $response = $this->adapter->get(
            'endpoint/method', [
                'foo' => 'bar'
            ]
        );

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('foo', $response);
        $this->assertEquals('bar', $response['foo']);
        $this->assertContains('https://flow.cl/api/endpoint/method', $response['url']);
        $this->assertContains('apiKey=apiKey', $response['url']);
        $this->assertContains('foo=bar', $response['url']);
        $this->assertContains('s=a5a6f48b749d656abaf396be8c49afd0e128950dd0cce2d64f38c56479736d2b', $response['url']);

    }

    public function testFailedGet()
    {
        $this->expectException(TransactionException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('get')->with(
            \Mockery::type('string')
        )->andReturn(new Response(
            401, [],
            json_encode($array = ['code' => 200, 'message' => 'Error message'])
        ));

        $this->adapter->get(
            'http://mockendpoint.com', [
                'foo' => 'bar'
            ]
        );
    }

    public function testUnreachableGet()
    {
        $this->expectException(AdapterException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');
        $logger->expects('error');

        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->mockClient->expects('get')
            ->with(\Mockery::type('string'))
            ->andThrowExceptions([
                new RequestException(
                    'Error Communicating with Server',
                    new Request('POST', json_encode(['foo' => 'bar']))
                )
            ]);

        $this->adapter->get(
            'http://mockendpoint.com', [
                'foo' => 'bar'
            ]
        );
    }

    public function testLogInfo()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');

        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->assertEmpty($this->adapter->logInfo('sample message'));
    }

    public function testSign()
    {
        $signature = hash_hmac('sha256', 'data', $secret = 'secret');

        $sign = $this->adapter->sign('data');

        $this->assertEquals($signature, $sign);
    }

    public function testLogDebug()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->assertEmpty($this->adapter->logDebug('sample message'));
    }

    public function testLogError()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('error');

        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->assertEmpty($this->adapter->logError('sample message'));
    }

    public function test__construct()
    {
        $adapter = new GuzzleAdapter($this->mockFlow, []);

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testSendsOptionalsArrayToJson()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');

        $this->mockClient->expects('post')->with(
            \Mockery::type('string'),
            \Mockery::type('array')
        )->andReturnUsing(function ($url, $params) {
            return new Response(
                200, [],
                json_encode(compact('url', 'params')
                ));
        });

        $response = $this->adapter->post('http://mockapp.com/post', [
            'key' => 'value',
            'optionals' => [
                'message' => 'must be json',
                'may' => [
                    'be' => 'multidimensional'
                ]
            ],
            'optional' => [
                'message' => 'also must be json',
                'may' => [
                    'be' => 'multidimensional'
                ]
            ]
        ]);

        $this->assertJson($response['params']['form_params']['optionals']);
        $this->assertJson($response['params']['form_params']['optional']);
    }

    public function testPostDisposesOfEmptyKeys()
    {

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');

        $this->mockClient->expects('post')->with(
            \Mockery::type('string'),
            \Mockery::type('array')
        )->andReturnUsing(function ($url, $params) {
            return new Response(
                200, [],
                json_encode(compact('url', 'params'))
            );
        });

        $response = $this->adapter->post('endpoint/method', [
            'key' => 'value',
            'cullNull' => null,
            'cullEmpty' => '',
        ]);

        $this->assertArrayNotHasKey('cullNull', $response['params']['form_params']);
        $this->assertArrayNotHasKey('cullEmpty', $response);
    }

    public function testGetDisposesOfEmptyKeys()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');

        $this->mockClient->expects('get')
            ->andReturnUsing(function ($args) {
                return new Response(
                    200, [],
                    json_encode(['string' => $args])
                );
            });

        $response = $this->adapter->get('serviceEndpoint', [
            'key' => 'value',
            'cullNull' => null,
            'cullEmpty' => '',
        ]);

        $this->assertContains('key=value', $response['string']);
        $this->assertNotContains('cullNull', $response['string']);
        $this->assertNotContains('cullEmpty', $response['string']);
    }

    public function testAddsWebhookSecretToAttributes()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');
        $this->mockFlow->expects('getWebhookSecret')->andReturn('123456789');

        $this->mockClient->expects('post')
            ->andReturnUsing(function ($url, $params) {
                return new Response(
                    200, [],
                    json_encode(
                        ['url' => $url] + $params['form_params']
                    )
                );
            });

        $response = $this->adapter->post('serviceEndpoint', [
            'urlConfirmation' => 'http://app.com/webhook/payment',
            'urlCallBack' => 'http://app.com/index.php?webhook=payment',
            'urlCallback' => 'http://app.com/webhook/card.php',
        ]);

        $this->assertEquals('http://app.com/webhook/payment?secret=123456789', $response['urlConfirmation']);
        $this->assertEquals('http://app.com/index.php?webhook=payment&secret=123456789', $response['urlCallBack']);
        $this->assertEquals('http://app.com/webhook/card.php?secret=123456789', $response['urlCallback']);
    }

    public function testDoesNotAddWebhookSecretIfHasSecret()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');
        $this->mockFlow->expects('getWebhookSecret')->andReturn('123456789');

        $this->mockClient->expects('post')
            ->andReturnUsing(function ($url, $params) {
                return new Response(
                    200, [],
                    json_encode(
                        ['url' => $url] + $params['form_params']
                    )
                );
            });

        $response = $this->adapter->post('serviceEndpoint', [
            'urlConfirmation' => 'http://app.com/webhook/payment?secret=123456789',
            'urlCallBack' => 'http://app.com/index.php?webhook=payment&secret=123456789',
            'urlCallback' => 'http://app.com/webhook/card.php?secret=123456789',
        ]);

        $this->assertEquals('http://app.com/webhook/payment?secret=123456789', $response['urlConfirmation']);
        $this->assertEquals('http://app.com/index.php?webhook=payment&secret=123456789', $response['urlCallBack']);
        $this->assertEquals('http://app.com/webhook/card.php?secret=123456789', $response['urlCallback']);
    }

}
