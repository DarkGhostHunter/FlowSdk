<?php

namespace Tests\Resources;

use DarkGhostHunter\FlowSdk\Contracts\ServiceInterface;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Services\BaseService;
use PHPUnit\Framework\TestCase;

class BasicResourceTest extends TestCase
{

    /** @var BasicResource */
    protected $resource;

    protected function setUp()
    {
        $this->resource = new BasicResource([]);
    }

    public function testResponse()
    {
        $this->resource->setResponse(\Mockery::instanceMock(BasicResponse::class));

        $this->assertInstanceOf(BasicResponse::class, $this->resource->getResponse());
    }

    public function testService()
    {
        $this->resource->setService(\Mockery::instanceMock(BaseService::class));

        $this->assertInstanceOf(ServiceInterface::class, $this->resource->getService());
    }

    public function testType()
    {
        $this->resource->setType('type');

        $this->assertEquals('type', $this->resource->getType());
    }

    public function testExists()
    {
        $this->assertFalse($this->resource->exists());

        $this->resource->setExists(true);

        $this->assertTrue($this->resource->exists());
    }

    public function testId()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $this->resource->set('foo', 'bar');

        $service->expects('getId')->andReturn('foo');

        $this->assertEquals('bar', $this->resource->getId());
    }

    public function testIdNull()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $this->resource->set('foo', 'bar');

        $service->expects('getId')->andReturn('notFoo');

        $this->assertNull($this->resource->getId());
    }

    public function testIdKey()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $this->resource->set('foo', 'bar');

        $service->expects('getId')->andReturn('foo');

        $this->assertEquals('foo', $this->resource->getIdKey());
    }

}
