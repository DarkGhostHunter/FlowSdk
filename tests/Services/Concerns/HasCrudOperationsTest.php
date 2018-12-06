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
     * @var AdapterInterface|\Mockery\MockInterface
     */
    protected $mockAdapter;

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

        $logger = \Mockery::instanceMock(LoggerInterface::class);

        $logger->expects('debug');

        $this->mockFlow->expects('setLogger');
        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->mockFlow->setLogger($logger);

        $this->mockAdapter = \Mockery::instanceMock(AdapterInterface::class);
    }

    public function testMakeAndSave()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $resource = $this->service->makeAndSave(['key' => 'value']);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }

    public function testGet()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('get')->andReturn(['foo' => 'bar']);

        $resource = $this->service->get('key');

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }

    public function testMakeAndCommit()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $resource = $this->service->makeAndCommit(['foo' => 'bar']);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }

    public function testCreate()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $resource = $this->service->create(['foo' => 'bar']);

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }

    public function testDelete()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $resource = $this->service->delete('foo');

        $this->assertInstanceOf(ResourceInterface::class, $resource);

    }

    public function testCommit()
    {
        $service = new class($this->mockFlow) extends BaseService {
            use HasCrudOperations;
            protected $permittedActions = [
                'commit' => true
            ];
        };

        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $response = $service->commit(['foo' => 'bar']);

        $this->assertInstanceOf(BasicResponse::class, $response);

    }

    public function testUpdate()
    {
        $this->mockFlow->expects('getAdapter')->andReturn($this->mockAdapter);
        $this->mockAdapter->expects('post')->andReturn(['foo' => 'bar']);

        $resource = $this->service->update('foo');

        $this->assertInstanceOf(ResourceInterface::class, $resource);
    }
}
