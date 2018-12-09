<?php

namespace DarkGhostHunter\FlowSdk;

use DarkGhostHunter\FlowSdk\Adapters\GuzzleAdapter;
use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Exceptions\Flow\InvalidUrlException;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Flow
 * @package DarkGhostHunter\FlowSdk
 *
 *
 * @method Services\Payment         payment()
 * @method Services\Refund          refund()
 * @method Services\Customer        customer()
 * @method Services\Plan            plan()
 * @method Services\Subscription    subscription()
 * @method Services\Coupon          coupon()
 * @method Services\Invoice         invoice()
 * @method Services\Settlement      settlement()
 */
class Flow
{
    /**
     * Environment to use
     *
     * @var bool
     */
    protected $isProduction = false;

    /**
     * Environment Endpoints
     *
     * @var array
     */
    protected $endpoints = [
        'sandbox' => 'https://flow.tuxpan.com/api',
        'production' => 'https://www.flow.cl/api',
    ];

    /**
     * Credentials to use
     *
     * @var Fluent
     */
    protected $credentials;

    /**
     * Services loaded
     *
     * @var array
     */
    protected $services = [];

    /**
     * Services Map to use for dynamic calling
     *
     * @var array
     */
    protected $servicesMap = [
        'coupon' => Services\Coupon::class,
        'customer' => Services\Customer::class,
        'invoice' => Services\Invoice::class,
        'payment' => Services\Payment::class,
        'plan' => Services\Plan::class,
        'refund' => Services\Refund::class,
        'settlement' => Services\Settlement::class,
        'subscription' => Services\Subscription::class,
    ];

    /**
     * Logger interface to log Flow requests
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Adapter to communicate with Flow
     *
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Default Return URLs where the User will hit after a Flow process finishes
     *
     * @var array
     */
    protected $returnUrls;

    /**
     * Default Webhook URLs that Flow will hit asynchronously
     *
     * @var array
     */
    protected $webhookUrls;

    /**
     * Sets a shared Webhook secret for the endpoints
     *
     * @var string
     */
    protected $webhookSecret;

    /*
    |--------------------------------------------------------------------------
    | Booting
    |--------------------------------------------------------------------------
    */

    /**
     * Flow constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    */

    /**
     * If Flow is using Production Environment
     *
     * @return string
     */
    public function isProduction()
    {
        return $this->isProduction;
    }

    /**
     * Set Flow Environment to production
     *
     * @param bool $isProduction
     */
    public function setProduction(bool $isProduction)
    {
        $this->isProduction = $isProduction;
    }

    /**
     * Get the Adapter
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Set the Adapter
     *
     * @param AdapterInterface $adapter
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Get the Base Endpoint depending on the environment
     *
     * @return mixed
     */
    public function getEndpoint()
    {
        return $this->endpoints[$this->isProduction ? 'production' : 'sandbox'];
    }

    /**
     * Get the Credentials for this service
     *
     * @return Fluent
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Set the Credentials for this service
     *
     * @param array $credentials
     * @throws \Exception
     */
    public function setCredentials(array $credentials)
    {
        $this->credentials = new Fluent(
            array_intersect_key($credentials, array_flip(['apiKey', 'secret']))
        );
    }

    /**
     * Gets the Logger interface being used by Flow
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Sets the Logger interface to use with Flow
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get the Return URLs
     *
     * @param string $key
     * @return array
     */
    public function getReturnUrls(string $key = null)
    {
        return $key
            ? $this->returnUrls[$key] ?? null
            : $this->returnUrls;
    }

    /**
     * Set the Return URLs
     *
     * @param array $returnUrls
     * @throws \Exception
     */
    public function setReturnUrls(array $returnUrls)
    {
        foreach ($returnUrls as &$returnUrl) {
            $returnUrl = $this->parseUrl($returnUrl);
        }

        $this->returnUrls = $returnUrls;
    }

    /**
     * Get the Webhook URLs
     *
     * @param string|null $key
     * @return array|string
     */
    public function getWebhookUrls(string $key = null)
    {
        return $key
            ? $this->webhookUrls[$key] ?? null
            : $this->webhookUrls;
    }

    /**
     * Gets a Webhook with the Secret if it's set
     *
     * @param string $key
     * @return string|null
     */
    public function getWebhookWithSecret(string $key)
    {
        if ($webhook = $this->getWebhookUrls($key)) {
            return $webhook . ($this->webhookSecret
                ? (strpos($webhook, '?') ? '&' : '?') . 'secret=' . $this->webhookSecret
                : ''
            );
        };

        return null;
    }

    /**
     * Set the Webhook URLs
     *
     * @param array $webhookUrls
     * @throws \Exception
     */
    public function setWebhookUrls(array $webhookUrls)
    {
        foreach ($webhookUrls as &$webhookUrl) {
            $webhookUrl = $this->parseUrl($webhookUrl);
        }

        $this->webhookUrls = $webhookUrls;
    }

    /**
     * Sets a Webhook Shared Secret
     *
     * @param string $webhookSecret
     */
    public function setWebhookSecret(string $webhookSecret)
    {
        $this->webhookSecret = $webhookSecret;
    }

    /**
     * Gets a Webhook Shared Secret
     *
     * @return string
     */
    public function getWebhookSecret()
    {
        return $this->webhookSecret;
    }

    /**
     * Parses a string and appends a Webhook Secret.
     *
     * @param string $url
     * @return string
     * @throws InvalidUrlException
     */
    protected function parseUrl(string $url)
    {
        $url = trim($url, '/');

        // Proceed if the URL is still a valid URL
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            // Return an URL with a '/' if is just a domain
            return preg_match('/(\.(?<tld>\w*))$/m', $url)
                ? $url . '/'
                : $url;
        }

        throw new InvalidUrlException($url);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a new Flow instance
     *
     * @param string $environment
     * @param array $credentials
     * @param LoggerInterface|null $logger
     * @return Flow
     * @throws \Exception
     */
    public static function make(string $environment, array $credentials, LoggerInterface $logger = null)
    {
        // If no logger is passed, we will use the null logger
        $flow = new static(
            $logger ?? new NullLogger()
        );

        // Set the credentials, or throw an Exception if there is none
        $flow->setCredentials($credentials);

        // Set the default Guzzle Adapter
        $flow->setAdapter(new GuzzleAdapter($flow));

        // Set the production environment if set explicitly
        $flow->setProduction($environment === 'production');

        // Return a new instance of Flow
        return $flow;
    }

    /*
    |--------------------------------------------------------------------------
    | Services Magic Factory
    |--------------------------------------------------------------------------
    */

    /**
     * Magically call the Service Resource Factory for convenience
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->servicesMap)) {
            return $this->services[$name]
                ?? $this->services[$name] = new $this->servicesMap[$name]($this);
        }

        throw new \BadMethodCallException("Method $name does not exists");
    }

}