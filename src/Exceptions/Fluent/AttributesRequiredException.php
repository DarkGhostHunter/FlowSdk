<?php

namespace DarkGhostHunter\FlowSdk\Exceptions\Fluent;

use DarkGhostHunter\FlowSdk\Exceptions\FlowSdkException;

class AttributesRequiredException extends \Exception implements FlowSdkException
{
    protected $message = 'The following attributes are required: ';

    public function __construct(array $required, array $attributes)
    {
        $diff = array_keys(array_diff_key(array_flip($required), $attributes));

        foreach ($diff as $dif) {
            $this->message .= $dif;
        }

        parent::__construct();
    }
}