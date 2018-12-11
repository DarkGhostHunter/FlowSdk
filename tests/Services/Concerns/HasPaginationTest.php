<?php

namespace Tests\Services\Concerns;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Responses\PagedResponse;
use DarkGhostHunter\FlowSdk\Services\BaseService;
use DarkGhostHunter\FlowSdk\Services\Concerns\HasPagination;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tests\Services\Concerns\Mocks\MockPagination;

class HasPaginationTest extends TestCase
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
     * @var BaseService|HasPagination
     */
    protected $service;

    protected function setUp()
    {
        $this->service = new MockPagination($this->mockFlow = \Mockery::instanceMock(Flow::class));

        $logger = \Mockery::instanceMock(LoggerInterface::class);

        $logger->expects('debug');

        $this->mockFlow->expects('setLogger');
        $this->mockFlow->expects('getLogger')->andReturn($logger);

        $this->mockFlow->setLogger($logger);

        $this->mockAdapter = \Mockery::instanceMock(AdapterInterface::class);
    }

    public function testPerPage()
    {
        $this->service->setPerPage(20);

        $this->assertEquals(20, $this->service->getPerPage());
    }

    public function testGetPage()
    {
        $this->mockFlow->expects('send')
            ->with('get', \Mockery::type('string'), [
                'start' => 20,
                'limit' => 10,
            ])
            ->andReturn([
                'total' => 200,
                'hasMore' => 1,
                'data' => [
                    0 => ['item' => 1],
                    1 => ['item' => 2]
                ]
            ]);

        $page = $this->service->getPage(3);

        $this->assertInstanceOf(PagedResponse::class, $page);
        $this->assertInternalType('array', $page->items);
        $this->assertTrue($page->hasMore);
        $this->assertEquals(200, $page->total);
        $this->assertInstanceOf(BasicResource::class, $page->items[0]);
        $this->assertInstanceOf(BasicResource::class, $page->items[1]);
    }
}
