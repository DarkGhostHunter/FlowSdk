<?php

namespace Tests\Resources;

use DarkGhostHunter\FlowSdk\Resources\SubscriptionResource;
use DarkGhostHunter\FlowSdk\Services\Subscription;
use PHPUnit\Framework\TestCase;

class SubscriptionResourceTest extends TestCase
{

    /** @var SubscriptionResource */
    protected $resource;

    /** @var Subscription|\Mockery\MockInterface */
    protected $mockService;

    protected $attributes = [
        'subscriptionId' => 'theSubscriptionId',
        'foo' => 'bar',
    ];

    protected function setUp()
    {
        $this->resource = new SubscriptionResource($this->attributes);

        $this->mockService = \Mockery::instanceMock(Subscription::class);

        $this->resource->setService($this->mockService);
        $this->resource->setExists(true);
    }

    public function testCancel()
    {
        $this->mockService->expects('getId')->andReturn('subscriptionId');

        $this->mockService->expects('cancel')->with('theSubscriptionId', true)
            ->andReturn(new SubscriptionResource([
                'foo' => 'bar'
            ]));

        $subscription = $this->resource->cancel(true);

        $this->assertTrue($subscription);
        $this->assertEquals('bar', $this->resource->foo);
    }

    public function testDoesntCancelIfCancelled()
    {
        $this->mockService->expects('getId')->andReturn('subscriptionId');

        $this->resource->status = 4;

        $subscription = $this->resource->cancel(true);

        $this->assertFalse($subscription);
    }
}
