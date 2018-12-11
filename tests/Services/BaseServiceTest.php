<?php

namespace Tests\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Contracts\ServiceInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Services\BaseService;
use PHPUnit\Framework\TestCase;
use Tests\Services\Mocks\MockBaseServiceGetEditableAttributes;
use Tests\Services\Mocks\MockBaseService;

class BaseServiceTest extends TestCase
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * @var Flow|\Mockery\MockInterface
     */
    protected $flow;

    protected function setUp()
    {
        $this->flow = \Mockery::instanceMock(Flow::class);

        $this->service = new MockBaseService($this->flow);
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
        $this->assertInternalType('array', $this->service->getVerbsMap());
    }

    public function testSetId()
    {
        $this->service->setId('id');

        $this->assertEquals('id', $this->service->getId());
    }

    public function testMake()
    {
        $this->flow->expects('getLogger')->andReturnUndefined();

        $resource = $this->service->make([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertInstanceOf(ServiceInterface::class, $resource->getService());
        $this->assertInternalType('bool', $resource->exists());
        $this->assertInternalType('string', $resource->getType());
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

        $service = new MockBaseServiceGetEditableAttributes($this->flow);

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
