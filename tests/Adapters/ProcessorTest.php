<?php

namespace Tests\Adapters;

use DarkGhostHunter\FlowSdk\Adapters\Processor;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\Fluid\Fluid;
use PHPUnit\Framework\TestCase;

class ProcessorTest extends TestCase
{

    /** @var Flow|\Mockery\MockInterface */
    protected $flow;

    /** @var Processor */
    protected $processor;

    /** @var string */
    protected $webhookSecret;

    protected function setUp()
    {
        $this->flow = \Mockery::instanceMock(Flow::class);

        $this->flow->expects('getCredentials')->andReturn(
            new Fluid(['apiKey' => 'theApiKey', 'secret' => 'theSecret'])
        );

        $this->flow->expects('getWebhookSecret')->andReturn(
            $this->webhookSecret = bin2hex(random_bytes(4))
        );

        $this->processor = new Processor($this->flow);
    }

    public function testIncorrectMethodReturnsNull()
    {
        $prepared = $this->processor->prepare(
            'noGet',
            ['foo' => 'bar']
        );

        $this->assertNull($prepared);
    }

    public function testGetRemovesEmptyKeys()
    {
        $prepared = $this->processor->prepare(
            'get',
            [
                'foo' => 'bar',
                'number' => 0,
                'deleteThis' => null,
                'deleteThisAlso' => [],
                'andThisToo' => ''
            ]
        );

        $this->assertInternalType('string', $prepared);
        $this->assertContains('number=0', $prepared);
        $this->assertNotContains('deleteThis', $prepared);
        $this->assertNotContains('deleteThisAlso', $prepared);
        $this->assertNotContains('andThisToo', $prepared);
    }

    public function testGetAppendsSignature()
    {
        $prepared = $this->processor->prepare(
            'get',
            [
                'foo' => 'bar',
            ]
        );

        $this->assertInternalType('string', $prepared);
        $this->assertContains('foo=bar', $prepared);
        $this->assertRegExp('/s=[a-zA-Z0-9]+$/', $prepared);
    }

    public function testPostOrderArrayByKey()
    {
        $prepared = $this->processor->prepare(
            'get',
            [
                'a' => '1',
                'b' => '2',
                'c' => '3',
                'z' => '4'
            ]
        );

        $this->assertInternalType('string', $prepared);
        $this->assertContains('a=1&apiKey=theApiKey&b=2&c=3&z=4', $prepared);
    }

    public function testPostTransformArrayKeysToJson()
    {
        $prepared = $this->processor->prepare(
            'post',
            [
                'mustBeJson' => [
                    'toJson' => 'yes'
                ],
                'thisIsJson' => json_encode(['isJson' => 'yes']),
            ]
        );

        $this->assertInternalType('array', $prepared);
        $this->assertJson($prepared['mustBeJson']);
        $this->assertJson($prepared['thisIsJson']);

    }

    public function testPostRemovesEmptyKeys()
    {
        $prepared = $this->processor->prepare(
            'post',
            [
                'foo' => 'bar',
                'number' => 0,
                'deleteThis' => null,
                'deleteThisAlso' => [],
                'andThisToo' => ''
            ]
        );

        $this->assertInternalType('array', $prepared);
        $this->assertArrayHasKey('number', $prepared);
        $this->assertEquals(0, $prepared['number']);
        $this->assertEquals('bar', $prepared['foo']);
        $this->assertArrayNotHasKey('deleteThis', $prepared);
        $this->assertArrayNotHasKey('deleteThisAlso', $prepared);
        $this->assertArrayNotHasKey('andThisToo', $prepared);
    }

    public function testPostAddSecretToWebhooks()
    {

        $prepared = $this->processor->prepare(
            'post',
            [
                'foo' => 'bar',
                'urlConfirmation' => 'http://app.com/webhook/payment',
                'urlCallBack' => 'http://app.com/index.php?type=card&lol=true',
                'urlCallback' => 'http://app.com/plan.php',
            ]
        );

        $this->assertContains("?secret=$this->webhookSecret", $prepared['urlConfirmation']);
        $this->assertContains("&secret=$this->webhookSecret", $prepared['urlCallBack']);
        $this->assertContains("?secret=$this->webhookSecret", $prepared['urlCallback']);
        $this->assertNotContains("secret=$this->webhookSecret", $prepared['foo']);

    }

    public function testPostDoesntAddSecretToWebhookIsSecretKeyExists()
    {
        $prepared = $this->processor->prepare(
            'post',
            [
                'foo' => 'bar',
                'urlConfirmation' => 'http://app.com/webhook/payment/secret/',
                'urlCallBack' => 'http://app.com/index.php?secret=mySecret',
                'urlCallback' => 'http://app.com/plan.php',
            ]
        );

        $this->assertContains("secret=$this->webhookSecret", $prepared['urlConfirmation']);
        $this->assertNotContains("secret=$this->webhookSecret", $prepared['urlCallBack']);
        $this->assertContains("secret=$this->webhookSecret", $prepared['urlCallback']);
        $this->assertNotContains("secret=$this->webhookSecret", $prepared['foo']);
    }

    public function testPostAppendSignature()
    {
        $prepared = $this->processor->prepare(
            'post',
            [
                'foo' => 'bar',
            ]
        );

        $this->assertRegExp('/^[a-zA-Z0-9]+$/', $prepared['s']);
    }

    public function testGetHasCorrectSignature()
    {
        $prepared = $this->processor->prepare(
            'get',
            ['foo' => 'bar', 'key' => 'value']
        );

        $signable = 'apiKey=theApiKey&foo=bar&key=value';

        $signature = hash_hmac('sha256', $signable, $this->flow->getCredentials()->secret);

        $this->assertContains("s=$signature", $prepared);
    }

    public function testPostHasCorrectSignature()
    {
        $prepared = $this->processor->prepare(
            'post',
            ['foo' => 'bar', 'key' => 0, 'deleteThis' => null, 'andThis' => []]
        );

        $signable = 'apiKey=theApiKey&foo=bar&key=0';

        $signature = hash_hmac('sha256', $signable, $this->flow->getCredentials()->secret);

        $this->assertEquals($signature, $prepared['s']);
    }
}
