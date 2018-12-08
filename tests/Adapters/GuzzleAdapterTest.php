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
            'https://flow.cl/api/post',
            \Mockery::on(function ($array) {
                $required = ['apiKey', 'key', 's'];
                return count(array_intersect_key(array_flip($required), $array['form_params'])) === count($required);
            })
        )->andReturn(new Response(
            200, [],
            json_encode($array = ['foo', 'bar'])
        ));

        $response = $this->adapter->post('post', ['key' => 'value']);

        $this->assertEquals($array, $response);
    }

    public function testFailedPost()
    {
        $this->expectException(TransactionException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('https://flow.cl/api');

        $this->mockClient->expects('post')->with(
            'https://flow.cl/api/post',
            \Mockery::type('array')
        )->andReturn(new Response(
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

        $this->mockClient->expects('post')->with(
            'https://flow.cl/api/post',
            \Mockery::type('array')
        )->andThrowExceptions([
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
            \Mockery::on(function ($string) {
                return strpos($string, 'https://flow.cl/api/get') === 0
                    && strpos($string, 'apiKey=')
                    && strpos($string, 'foo=')
                    && strpos($string, 's=');
            })
        )->andReturn(new Response(
            200, [],
            json_encode($array = ['foo' => 'bar'])
        ));

        $response = $this->adapter->get(
            'get', [
                'foo' => 'bar'
            ]
        );

        $this->assertEquals($array, $response);

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
            \Mockery::on(function ($string) {
                return strpos($string, 'https://flow.cl/api/get') !== 0
                    && strpos($string, 'apiKey=')
                    && strpos($string, 'foo=')
                    && strpos($string, 's=');
            })
        )->andReturn(new Response(
            401, [],
            json_encode($array = ['code' => 200, 'message' => 'Error message'])
        ));

        $response = $this->adapter->get(
            'http://mockendpoint.com', [
                'foo' => 'bar'
            ]
        );

        $this->assertEquals($array, $response);
    }

    public function testUnreachableGet()
    {
        $this->expectException(AdapterException::class);

        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');
        $logger->expects('error');

        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->mockClient->expects('get')->with(
            \Mockery::on(function ($string) {
                return strpos($string, 'apiKey=')
                    && strpos($string, 'foo=')
                    && strpos($string, 's=');
            })
        )->andThrowExceptions([
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

    public function testSendsOptionalArrayToJson()
    {
        $logger = \Mockery::instanceMock(LoggerInterface::class);
        $logger->expects('info');
        $logger->expects('debug');

        $this->mockFlow->expects('getLogger')->andReturn($logger);
        $this->mockFlow->expects('getEndpoint')->andReturn('http://flow.com/api');

        $this->mockClient->expects('post')->with(
            \Mockery::type('string'),
            \Mockery::on(function ($array) {
                $required = ['apiKey', 'key', 'optional', 's'];
                $hasRequired = count(array_intersect_key(array_flip($required), $array['form_params'])) === count($required);

                $optionalsIsJson = is_string($array['form_params']['optional'])
                    && !!json_decode($array['form_params']['optional']);

                return $hasRequired && $optionalsIsJson;
            })
        )->andReturn(new Response(
            200, [],
            json_encode($array = ['foo', 'bar'])
        ));

        $response = $this->adapter->post('http://mockapp.com/post', [
            'key' => 'value',
            'optional' => [
                'message' => 'must be json'
            ]
        ]);

        $this->assertEquals($array, $response);
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
            \Mockery::on(function ($array) {
                $required = ['apiKey', 'key', 'optionals', 's'];
                $hasRequired = count(array_intersect_key(array_flip($required), $array['form_params'])) === count($required);

                $optionalsIsJson = is_string($array['form_params']['optionals'])
                    && !!json_decode($array['form_params']['optionals']);

                return $hasRequired && $optionalsIsJson;
            })
        )->andReturn(new Response(
            200, [],
            json_encode($array = ['foo', 'bar'])
        ));

        $response = $this->adapter->post('http://mockapp.com/post', [
            'key' => 'value',
            'optionals' => [
                'message' => 'must be json'
            ]
        ]);

        $this->assertEquals($array, $response);
    }
}
