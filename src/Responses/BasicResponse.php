<?php

namespace DarkGhostHunter\FlowSdk\Responses;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class BasicResponse extends Fluent
{
    /**
     * Returns a fully functional string
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url && $this->token
            ? $this->url  . '?token=' . $this->token
            : null;
    }
}