<?php

namespace Tests\Services\Concerns;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Services\BaseService;
use DarkGhostHunter\FlowSdk\Services\Concerns\HasCrudOperations;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class HasCrudOperationsTest extends TestCase
{

    /**
     * @var Flow|\Mockery\MockInterface
     */
    protected $mockFlow;

    /**
     * @var BaseService|HasCrudOperations
     */
    protected $service;

    protected function setUp()
    {
        $this->service = new class($this->mockFlow = \Mockery::instanceMock(Flow::class)) extends BaseService {
            use HasCrudOperations;
        };

        $this->service->setId('serviceId');

        $logger = \Mockery::instanceMock(LoggerInterface::class);

        $logger->expects('debug');

        $this->mockFlow->expects('setLogger');
        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->mockFlow->setLogger($logger);
    }

    public function testGet()
    {
        $this->mockFlow->expects('send')
            ->with('get', \Mockery::type('string'), [$this->service->getId() => 'bar'])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $resource = $this->service->get('bar');

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertEquals('bar', $resource->{$this->service->getId()});
    }

    public function testCreate()
    {
        $this->mockFlow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $resource = $this->service->create(['foo' => 'bar']);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testDelete()
    {
        $this->mockFlow->expects('send')
            ->with('post', \Mockery::type('string'), [$this->service->getId() => 'bar'])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $resource = $this->service->delete('bar');

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertEquals('bar', $resource->{$this->service->getId()});

    }

    public function testCommit()
    {
        $service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'commit' => true
            ];
        };

        $this->mockFlow->expects('send')
            ->with('post', \Mockery::type('string'), ['foo' => 'bar'])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $response = $service->commit(['foo' => 'bar']);

        $this->assertInstanceOf(BasicResponse::class, $response);

    }

    public function testUpdate()
    {
        $this->mockFlow->expects('send')
            ->with('post', \Mockery::type('string'), [
                $this->service->getID() => 'theResourceId',
                'foo' => 'bar'
            ])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $resource = $this->service->update('theResourceId', ['foo' => 'bar']);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }

    public function testUpdateWithOnly()
    {
        $this->mockFlow->expects('send')
            ->with('post', \Mockery::type('string'), [
                $this->service->getID() => 'theResourceId',
                'foo' => 'bar'
            ])
            ->andReturnUsing(function ($method, $endpoint, $data) {
                return $data;
            });

        $this->service->setEditableAttributes([
            'foo'
        ]);

        $resource = $this->service->update('theResourceId', [
            'foo' => 'bar',
            'key' => 'value'
        ]);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
        $this->assertNull($resource->key);
        $this->assertEquals('bar', $resource->foo);
    }

    public function testCantGet()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'get'    => false,
            ];
        };

        $this->service->get('id');

    }

    public function testCantCommit()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'commit'    => false,
            ];
        };

        $this->service->commit([]);

    }

    public function testCantCreate()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'create'    => false,
            ];
        };

        $this->service->create([]);

    }

    public function testCantUpdate()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'update'    => false,
            ];
        };

        $this->service->update([]);

    }

    public function testCantDelete()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'delete'    => false,
            ];
        };

        $this->service->delete('id');

    }
}
