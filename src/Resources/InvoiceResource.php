<?php

namespace DarkGhostHunter\FlowSdk\Resources;

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