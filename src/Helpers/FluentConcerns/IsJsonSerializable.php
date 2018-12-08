<?php

namespace DarkGhostHunter\FlowSdk\Helpers\FluentConcerns;

trait IsJsonSerializable
{
    /**
     * Specify data which should be serialized to JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

}