<?php

namespace Tests\Adapters;

use DarkGhostHunter\FlowSdk\Adapters\GuzzleAdapter;
use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Exceptions\Adapter\AdapterException;
use DarkGhostHunter\FlowSdk\Exceptions\Transactions\TransactionException;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\Fluid\Fluid;
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

        $this->mockFlow->expects('getCredentials')->andReturn(new Fluid([
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

        $response = $this->adapter->post('https://flow.cl/api/endpoint/method', ['key' => 'value']);

        $this->assertArrayHasKey('url', $response);
        $this->assertEquals('https://flow.cl/api/endpoint/method', $response['url']);
        $this->assertEquals([
            'key' => 'value',
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
            'https://flow.cl/api/endpoint/method',
            [
                'foo' => 'bar'
            ]
        );


        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('foo', $response);
        $this->assertEquals('bar', $response['foo']);
        $this->assertContains('https://flow.cl/api/endpoint/method', $response['url']);

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


}
