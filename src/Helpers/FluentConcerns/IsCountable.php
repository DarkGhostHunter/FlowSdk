<?php

namespace DarkGhostHunter\FlowSdk\Helpers\FluentConcerns;

trait IsCountable
{
    /**
     * Count all the keys in the class as an array
     *
     * @return int
     */
    public function count()
    {
        return count($this->toArray());
    }
}