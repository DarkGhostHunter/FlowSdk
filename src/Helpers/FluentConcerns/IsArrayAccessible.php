<?php

namespace DarkGhostHunter\FlowSdk\Helpers\FluentConcerns;

trait IsArrayAccessible
{
    /**
     * Whether a offset exists
     *
     * @param $offset
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset)
    {
        return $this->attributes[$offset] ?? false;
    }

    /**
     * Offset to retrieve
     *
     * @param $offset
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset] ?? null;
    }

    /**
     * Offset to set
     *
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        $this->attributes[$offset] = $value ?? null;
    }

    /**
     * Offset to unset
     *
     * @param $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }
}