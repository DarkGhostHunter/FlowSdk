<?php

namespace DarkGhostHunter\FlowSdk\Services;

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
    public function cancel(string $id, bool $atPeriodEnd)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Subscription $id" . ($atPeriodEnd ? ' at period end.' : '.'));

        return $this->make(
            $this->flow->getAdapter()->post(
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
    public function addCoupon(string $subscriptionId, string $couponId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Adding Coupon $couponId to $subscriptionId");

        return $this->make(
            $this->flow->getAdapter()->post(
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
    public function removeCoupon(string $subscriptionId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Removing Coupons from $subscriptionId");

        return $this->make(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/deleteCoupon',
                [
                    'subscriptionId' => $subscriptionId,
                ]
            )
        );
    }
}