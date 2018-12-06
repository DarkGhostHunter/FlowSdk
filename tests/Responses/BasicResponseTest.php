<?php

namespace Tests\Responses;

use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use PHPUnit\Framework\TestCase;

class BasicResponseTest extends TestCase
{

    public function testGetUrl()
    {
        $response = new BasicResponse([
            'url' => 'http://test.com/resource',
            'token' => bin2hex(random_bytes(16))
        ]);

        $this->assertNotFalse(filter_var($response->getUrl(), FILTER_VALIDATE_URL));
    }
}
