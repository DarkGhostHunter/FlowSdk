<?php

namespace DarkGhostHunter\FlowSdk\Exceptions\Flow;

use DarkGhostHunter\FlowSdk\Exceptions\FlowSdkException;
use Throwable;

class InvalidUrlException extends \Exception implements FlowSdkException
{
    protected $message = "Cannot use '%s', it's not a valid URL.";

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = strtr($this->message, '%s', $message);

        parent::__construct($message, $code, $previous);
    }
}