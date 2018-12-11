<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Resources\SubscriptionResource;

class Subscription extends BaseService
{
    use Concerns\HasCrudOperations,
        Concerns\HasPagination;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'subscriptionId';

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [
        'update' => 'changeTrial',
    ];

    /**
     * Resource Class to instantiate
     *
     * @var SubscriptionResource
     */
    protected $resourceClass = SubscriptionResource::class;

    /**
     * Permitted actions of the Service Resources
     *
     * @var array
     */
    protected $permittedActions = [
        'get'    => true,
        'commit' => false,
        'create' => true,
        'update' => true,
        'delete' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | Existence
    |--------------------------------------------------------------------------
    */


    /**
     * Calculates the Resource existence based its attributes (or presence)
     *
     * @param ResourceInterface $resource
     * @return bool
     */
    protected function calcResourceExistence(ResourceInterface $resource)
    {
        // It exists if "cancelled at" is empty, or if is before now.
        return ! ($resource->cancel_at && strtotime($resource->cancel_at) < time());
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Cancels a Subscription
     *
     * @param string $id
     * @param bool $atPeriodEnd
     * @return \DarkGhostHunter\FlowSdk\Contracts\ResourceInterface|\DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function cancel($id, $atPeriodEnd)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Subscription $id" . ($atPeriodEnd ? ' at period end.' : '.'));

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/cancel',
                [
                    'subscriptionId' => $id,
                    'at_period_end' => (int) $atPeriodEnd,
                ]
            )
        );
    }

    /**
     * Adds a Coupon to a Subscription
     *
     * @param string $subscriptionId
     * @param string $couponId
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function addCoupon($subscriptionId, $couponId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Adding Coupon $couponId to $subscriptionId");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/addCoupon',
                [
                    'subscriptionId' => $subscriptionId,
                    'couponId' => $couponId,
                ]
            )
        );
    }

    /**
     * Removes a Coupon from the Subscription
     *
     * @param string $subscriptionId
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function removeCoupon($subscriptionId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Removing Coupons from $subscriptionId");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/deleteCoupon',
                [
                    'subscriptionId' => $subscriptionId,
                ]
            )
        );
    }
}