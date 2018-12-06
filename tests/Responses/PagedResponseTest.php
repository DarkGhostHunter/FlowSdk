<?php

namespace Tests\Responses;

use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Responses\PagedResponse;
use PHPUnit\Framework\TestCase;

class PagedResponseTest extends TestCase
{

    /** @var PagedResponse */
    protected $response;

    protected function setUp()
    {
        $this->response = new PagedResponse([
            'total' => 14,
            'hasMore' => 0,
            'page' => 2,
            'items' => [
                new BasicResource(['item' => 1]), new BasicResource(['item' => 2]),
                new BasicResource(['item' => 3]), new BasicResource(['item' => 4]),
            ]
        ]);
    }

    public function testPage()
    {
        $this->response->setPageAttribute(1);

        $this->assertEquals(1, $this->response->page);
        $this->assertEquals(1, $this->response->getPageAttribute());
    }

    public function testItems()
    {
        $this->response->setItemsAttribute($items = [
            new BasicResource(['item' => 1]), new BasicResource(['item' => 2]),
            new BasicResource(['item' => 3]), new BasicResource(['item' => 4]),
        ]);

        $this->assertIsArray($this->response->items);
        $this->assertIsArray($this->response->getItemsAttribute());
        $this->assertInstanceOf(BasicResource::class, $this->response->items[0]);
        $this->assertInstanceOf(BasicResource::class, $this->response->items[1]);
        $this->assertInstanceOf(BasicResource::class, $this->response->items[2]);
        $this->assertInstanceOf(BasicResource::class, $this->response->items[3]);
    }

    public function testHasMore()
    {
        $this->response->setHasMoreAttribute(0);

        $this->assertFalse($this->response->hasMore);
        $this->assertFalse($this->response->getHasMoreAttribute());

        $this->response->setHasMoreAttribute(1);

        $this->assertTrue($this->response->hasMore);
        $this->assertTrue($this->response->getHasMoreAttribute());
    }

    public function testTotal()
    {
        $this->response->setTotalAttribute(20);

        $this->assertEquals(20, $this->response->total);
        $this->assertEquals(20, $this->response->getTotalAttribute());
    }
}
