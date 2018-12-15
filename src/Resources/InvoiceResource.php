<?php

namespace DarkGhostHunter\FlowSdk\Resources;

/**
 * Class InvoiceResource
 * @package DarkGhostHunter\FlowSdk\Resources
 *
 * @property-read int $id
 * @property-read string $subscriptionId
 * @property-read string $customerId
 * @property-read string $created
 * @property-read string $subject
 * @property-read string $currency
 * @property-read float $amount
 * @property-read string $period_start
 * @property-read string $period_end
 * @property-read int $attemp_count
 * @property-read int $attemped
 * @property-read string $next_attemp_date
 * @property-read int $status
 * @property-read int $trxId
 * @property-read array $items
 * @property-read array $payment
 */
class InvoiceResource extends BasicResource
{
    /**
     * Cancels this Invoice if it's pending
     *
     * @return bool
     */
    public function cancel()
    {
        if (!$this->status && !$this->attemped && $cancelled = $this->service->cancel($this->getId())) {
            $this->setAttributes(
                $cancelled->toArray()
            );
            return true;
        }
        return false;
    }
}