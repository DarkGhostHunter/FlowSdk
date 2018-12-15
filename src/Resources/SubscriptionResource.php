<?php

namespace DarkGhostHunter\FlowSdk\Resources;

/**
 * Class SubscriptionResource
 * @package DarkGhostHunter\FlowSdk\Resources
 *
 * @property-read string $subscriptionId
 * @property-read string $planId
 * @property-read string $plan_name
 * @property-read string $customerId
 * @property-read string $created
 * @property-read string $subscription_start
 * @property-read string $period_start
 * @property-read string $period_end
 * @property-read string $next_invoice_date
 * @property-read int $trial_period_days
 * @property-read string $trial_start
 * @property-read string $trial_end
 * @property-read int $cancel_at_period_end
 * @property-read string $cancel_at
 * @property-read int $days_until_due
 * @property-read int $status
 * @property-read int $morose
 * @property-read string $discount
 * @property-read string $invoices
 */
class SubscriptionResource extends BasicResource
{
    /**
     * Cancels the Subscription
     *
     * @param bool $atPeriodEnd
     * @return $this|bool
     */
    public function cancel($atPeriodEnd = false)
    {
        if ($this->exists() && $cancelled = $this->service->cancel($this->getId(), $atPeriodEnd)) {
            $this->setAttributes(
                $cancelled->toArray()
            );
            return true;
        }
        return false;
    }
}