<?php

namespace DarkGhostHunter\FlowSdk\Exceptions\Adapter;

use DarkGhostHunter\FlowSdk\Exceptions\FlowSdkException;
use Exception;
use Throwable;

class AdapterException extends Exception implements FlowSdkException
{
    protected $message = 'Flow did not respond successfully.';

    public function __construct($message = "", $code = 0, $previous = null)
    {

        if (!empty($message)) {
            $this->message .= "\nTransaction data: $message";
        }

        parent::__construct($this->message, $code, $previous);
    }
}