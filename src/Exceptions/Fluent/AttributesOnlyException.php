<?php

namespace DarkGhostHunter\FlowSdk\Exceptions\Fluent;

use DarkGhostHunter\FlowSdk\Exceptions\FlowSdkException;
use Throwable;

class AttributesOnlyException extends \Exception implements FlowSdkException
{
    protected $message = 'This class can only declare the following attributes: ';

    public function __construct(array $attributes = [], $code = 0, Throwable $previous = null)
    {
        $this->message .= implode(', ', $attributes) . '.';

        parent::__construct($this->message, $code, $previous);
    }
}