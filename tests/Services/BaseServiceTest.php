<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Contracts\ServiceInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Services\BaseService;
use PHPUnit\Framework\TestCase;

class BaseServiceTest extends TestCase
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * @var Flow|\Mockery\MockInterface
     */
    protected $mockFlow;

    protected function setUp()
    {
        $this->mockFlow = \Mockery::instanceMock(Flow::class);

        $this->service = new class ($this->mockFlow) extends BaseService {
            protected $endpoint = 'endpoint';
        };
    }

    public function testGetId()
    {
        $this->assertEquals('token', $this->service->getId());
    }

    public function testGetEndpoint()
    {
        $this->assertEquals('endpoint', $this->service->getEndpoint());
    }

    public function testGetVerbsMap()
    {
        $this->assertIsArray($this->service->getVerbsMap());
    }

    public function test__construct()
    {
        $service = new class (\Mockery::instanceMock(Flow::class)) extends BaseService {};

        $this->assertInstanceOf(BaseService::class, $service);
    }

    public function testSetId()
    {
        $this->service->setId('id');

        $this->assertEquals('id', $this->service->getId());
    }

    public function testMake()
    {
        $this->mockFlow->expects('getLogger')->andReturnUndefined();

        $resource = $this->service->make([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertInstanceOf(ServiceInterface::class, $resource->getService());
        $this->assertIsBool($resource->exists());
        $this->assertStringContainsString('baseService', $resource->getType());
    }

    public function testCan()
    {
        $this->assertTrue($this->service->can('get'));
        $this->assertFalse($this->service->can('commit'));
        $this->assertTrue($this->service->can('create'));
        $this->assertTrue($this->service->can('update'));
        $this->assertTrue($this->service->can('delete'));
    }

    public function testGetEditableAttributes()
    {
        $this->assertNull($this->service->getEditableAttributes());

        $service = new class ($this->mockFlow) extends BaseService {
            protected $editableAttributes = ['foo', 'bar'];
        };

        $this->assertEquals(['foo', 'bar'], $service->getEditableAttributes());
    }

    public function testSetEditableAttributes()
    {
        $this->service->setEditableAttributes($array = ['foo', 'bar']);

        $this->assertEquals($array, $this->service->getEditableAttributes());
    }

    public function testSetEndpoint()
    {
        $this->service->setEndpoint('endpoint');

        $this->assertEquals('endpoint', $this->service->getEndpoint());
    }

    public function testSetVerbsMap()
    {
        $this->service->setVerbsMap($array = [
            'get' => 'getMap',
            'delete' => 'cancel'
        ]);

        $this->assertEquals($array, $this->service->getVerbsMap());
    }
}