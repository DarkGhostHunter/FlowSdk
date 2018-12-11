<?php

namespace DarkGhostHunter\FlowSdk\Adapters;

use DarkGhostHunter\FlowSdk\Contracts\AdapterInterface;
use DarkGhostHunter\FlowSdk\Exceptions\Adapter\AdapterException;
use DarkGhostHunter\FlowSdk\Exceptions\Transactions\TransactionException;
use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use GuzzleHttp\Client;

class GuzzleAdapter implements AdapterInterface
{
    /**
     * GuzzleClient
     *
     * @var Client
     */
    protected $client;

    /**
     * Credentials
     *
     * @var Fluent
     */
    protected $credentials;

    /**
     * @var Flow
     */
    private $flow;

    /**
     * @inheritdoc
     */
    public function __construct(Flow $flow, array $options = null)
    {
        $this->flow = $flow;
        $this->credentials = $flow->getCredentials();
        $this->client = new Client($options ?? []);
    }

    /**
     * Return the underneath HTTP Client
     *
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the HTTP Client to use with the Adapter
     *
     * @param $client
     * @return mixed
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     * @throws TransactionException
     * @throws AdapterException
     */
    public function post(string $endpoint, array $data): array
    {

        $path = parse_url($endpoint, PHP_URL_PATH);

        // Log the Transactions
        $this->logInfo("Sending Transaction to $path: \n" . json_encode($data));

        try {
            $response = $this->client->post(
                $endpoint, ['form_params' => $data]
            );
        } catch (\Exception $exception) {
            $this->logError("Error Sending transaction to $path:\n" . json_encode($data));
            throw new AdapterException($path, 0, $exception);
        }

        if ($response->getStatusCode() === 200) {
            $this->logDebug('Returning decoded response contents.');
            return json_decode($response->getBody()->getContents(), true);

        }

        throw new TransactionException($response);

    }

    /**
     * @inheritdoc
     * @throws AdapterException
     * @throws TransactionException
     */
    public function get(string $endpoint): array
    {

        $path = parse_url($endpoint, PHP_URL_PATH);

        // Log the Transactions
        $this->logInfo("Retrieving Resource from '$path'.");

        try {
            $response = $this->client->get($endpoint);
        } catch (\Exception $exception) {
            $this->logError("Error Retrieving Resource from '$path' with " . parse_url($endpoint, PHP_URL_QUERY) . '.');
            throw new AdapterException($endpoint, 0, $exception);
        }

        if ($response->getStatusCode() === 200) {
            $this->logDebug('Returning decoded response contents.');
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new TransactionException($response);
    }

    /**
     * Logs informative process
     *
     * @param string $message
     * @return void
     */
    public function logInfo(string $message)
    {
        $this->flow->getLogger()->info($message);
    }

    /**
     * Logs an Error
     *
     * @param string $message
     * @return void
     */
    public function logError(string $message)
    {
        $this->flow->getLogger()->error($message);
    }

    /**
     * Logs a Debug
     *
     * @param string $message
     * @return void
     */
    public function logDebug(string $message)
    {
        $this->flow->getLogger()->debug($message);
    }
}