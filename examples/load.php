<?php

use DarkGhostHunter\FlowSdk\Flow;
use Psr\Log\LogLevel;
use Katzgrau\KLogger\Logger;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__, '.env');
$dotenv->load();

try {
    if (!getenv('FLOW_SANDBOX_API_KEY') || !getenv('FLOW_SANDBOX_SECRET')) {
        throw new Exception();
    }
} catch (\Exception $exception) {
    echo '<div class="alert alert-danger text-center">
    You don\'t have set your credentials! Copy the <code>.env.dist</code> to <code>.env</code> and add your sandbox credentials there.
</div>';
}

/** Just to have the same Flow instance available globally */
class FlowInstance {
    static $instance;
    public static function getFlow()
    {
        return self::$instance
            ? self::$instance
            : self::$instance = Flow::make('sandbox', [
                'apiKey' => getenv('FLOW_SANDBOX_API_KEY'),
                'secret' => getenv('FLOW_SANDBOX_SECRET'),
            ], new Logger(__DIR__ . '/logs', LogLevel::DEBUG));
    }
}


/** Returns the current URL Path*/
if (!function_exists('currentUrlPath')) {
    function currentUrlPath(string $append = '')
    {
        // Get the request path with the script name
        $requestPath = trim($_SERVER['REQUEST_URI'], '/');
        
        $requestPath = explode('?', $requestPath, 2)[0];

        // Form the URL and add the path.
        $url = $_SERVER['HTTPS'] ?? false ? 'https' : 'http' .
                '://' . $_SERVER['HTTP_HOST'] .
                ((int)$_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']) .
                '/' . $requestPath;

        // Parse the $url
        $parsed = pathinfo($url);

        // If the URL has a filename, strip it from it to leave only the path.
        if(isset($parsed['filename']) && isset($parsed['extension'])) {
            $url = str_replace($parsed['basename'], '', $url);
        }

        // Clean the path and return it with the appended filename (if its set)
        return trim($url, '/') . ($append ? '/' . $append : '');
    }
}
