<?php

namespace DarkGhostHunter\FlowSdk\Resources;

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
        if ($cancelled = $this->service->cancel($this->getId(), $atPeriodEnd)) {
            $this->setAttributes(
                $cancelled->toArray()
            );
            return $this;
        }
        return false;
    }
}