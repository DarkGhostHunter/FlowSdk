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

    /**
     * Serializes the object to a JSON string
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->jsonSerialize());
    }

}