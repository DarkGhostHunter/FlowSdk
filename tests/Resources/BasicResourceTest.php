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

        $this->resource->setExists(false);
        $this->assertFalse($this->resource->exists());

        $this->resource->setExists();
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

    public function testCommit()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $service->expects('commit')->with(
            \Mockery::type('array')
        )->andReturn(
            new BasicResponse([
                'foo' => 'bar'
            ])
        );

        $response = $this->resource->commit();

        $this->assertInstanceOf(BasicResponse::class, $response);
        $this->assertEquals('bar', $response->foo);
    }

    public function testDoesntCommit()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $this->resource->setExists(true);
        $this->resource->setResponse(new BasicResponse([]));

        $response = $this->resource->commit();

        $this->assertFalse($response);
    }

    public function testCantCommit()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $service->expects('commit')->with(
            \Mockery::type('array')
        )->andThrowExceptions([
            new \BadMethodCallException('commit')
        ]);

        $this->resource->commit();
    }

    public function testRefresh()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();
        $this->resource->resourceId = 'theResourceId';

        $service->expects('get')->with('resourceId', 'theResourceId')
            ->andReturn(
            new BasicResource([
                'foo' => 'bar'
            ])
        );

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->refresh();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertTrue($bool);
        $this->assertEquals('bar', $this->resource->foo);
    }

    public function testDoesntRefresh()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->resourceId = 'theResourceId';

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->refresh();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertFalse($bool);
    }

    public function testDoesntRefreshBecauseDoesntExists()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();
        $this->resource->resourceId = 'theResourceId';

        $service->expects('get')
            ->with('resourceId', 'theResourceId')
            ->andThrowExceptions([
                new \BadMethodCallException('commit')
            ]);

        $service->expects('getId')->andReturn('resourceId');

        $this->resource->refresh();
    }

    public function testSaveCreates()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->foo = 'bar';

        $service->expects('create')
            ->with(['foo' => 'bar'])
            ->andReturn(
                new BasicResource([
                    'resourceId' => 'theResourceId',
                    'foo' => 'bar'
                ])
            );

        $bool = $this->resource->save();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertTrue($bool);
        $this->assertEquals('bar', $this->resource->foo);
        $this->assertEquals('theResourceId', $this->resource->resourceId);
    }

    public function testSaveUpdates()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();

        $this->resource->foo = 'bar';
        $this->resource->resourceId = 'theResourceId';

        $service->expects('update')
            ->with('theResourceId', [
                'resourceId' => 'theResourceId',
                'foo' => 'bar'
            ])
            ->andReturn(
                new BasicResource([
                    'resourceId' => 'theResourceId',
                    'foo' => 'bar'
                ])
            );

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->save();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertTrue($bool);
        $this->assertTrue($this->resource->exists());
        $this->assertEquals('bar', $this->resource->foo);
        $this->assertEquals('theResourceId', $this->resource->resourceId);
    }

    public function testSaveDoesntCreate()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->foo = 'bar';

        $service->expects('create')
            ->with(['foo' => 'bar'])
            ->andThrowExceptions([
                new \BadMethodCallException()
            ]);

        $this->resource->save();
    }

    public function testSaveDoesntUpdates()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();

        $this->resource->foo = 'bar';
        $this->resource->resourceId = 'theResourceId';

        $service->expects('update')
            ->with('theResourceId', [
                'resourceId' => 'theResourceId',
                'foo' => 'bar'
            ])
            ->andThrowExceptions([
                new \BadMethodCallException()
            ]);

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->save();
    }

    public function testDeletes()
    {
        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();

        $this->resource->foo = 'bar';
        $this->resource->resourceId = 'theResourceId';

        $service->expects('delete')
            ->with('theResourceId')
            ->andReturn(
                new BasicResource([
                    'resourceId' => 'theResourceId',
                    'foo' => 'bar'
                ])
            );

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->delete();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertTrue($bool);
        $this->assertFalse($this->resource->exists());
        $this->assertEquals('bar', $this->resource->foo);
        $this->assertEquals('theResourceId', $this->resource->resourceId);
    }

    public function testDoesntDeletes()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));
        $this->resource->setExists();

        $this->resource->foo = 'bar';
        $this->resource->resourceId = 'theResourceId';

        $service->expects('delete')
            ->with('theResourceId')
            ->andThrowExceptions([
                new \BadMethodCallException()
            ]);

        $service->expects('getId')->andReturn('resourceId');

        $bool = $this->resource->delete();
    }

    public function testDeletesReturnFalse()
    {

        $this->resource->setService($service = \Mockery::instanceMock(ServiceInterface::class));

        $this->resource->foo = 'bar';
        $this->resource->resourceId = 'theResourceId';

        $bool = $this->resource->delete();

        $this->assertInstanceOf(BasicResource::class, $this->resource);
        $this->assertFalse($bool);
        $this->assertFalse($this->resource->exists());
        $this->assertEquals('bar', $this->resource->foo);
        $this->assertEquals('theResourceId', $this->resource->resourceId);
    }

}
