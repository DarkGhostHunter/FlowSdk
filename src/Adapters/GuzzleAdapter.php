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
    public function post(string $endpoint, array $data, $options = null) : array
    {

        $original = $data;

        // Add the API Key
        $data['apiKey'] = $this->credentials->apiKey;

        // Log the Transactions
        $this->logInfo("Sending Transaction to $endpoint: \n". json_encode($original));

        // Sort the parameters
        ksort($data);

        // Transform the Optional array as json if it's an array
        if (is_array($data['optional'] ?? false)) {
            $data['optional'] = json_encode($data['optional']);
        }

        // Transform the Optionals array as json if it's an array
        if (is_array($data['optionals'] ?? false)) {
            $data['optionals'] = json_encode($data['optionals']);
        }

        // Create the sign-able string
        $signature = implode('&', array_map(
            function ($value, $key) {
                return "$key=$value";
            },
            $data,
            array_keys($data)
        ));

        // Add the signed string
        $data['s'] = $this->sign($signature);

        try {
            $response = $this->client->post(
                $this->flow->getEndpoint() . '/' . $endpoint,
                ['form_params' => $data]
            );
        } catch (\Exception $exception) {
            $this->logError("Error Sending transaction to $endpoint:\n". json_encode($original));
            throw new AdapterException(json_encode($original), 0, $exception);
        }

        if ($response->getStatusCode() === 200) {

            $this->logDebug('Response Code received: ' . $response->getStatusCode());
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
    public function get(string $endpoint, array $params, array $options = null) : array
    {

        $original = $params;

        // Add the API Key
        $params['apiKey'] = $this->credentials->apiKey;

        // Log the Transactions
        $this->logInfo("Retrieving Resource from $endpoint:\n". json_encode($original));

        // Sort the parameters
        ksort($params);

        // Create the signature with the parameters and the signature
        $signature = '?' . ($params = http_build_query($params, null, '&')) . '&s=' . $this->sign($params);

        $this->logDebug("Signature created: $signature");

        try {
            $response = $this->client->get(
                $this->flow->getEndpoint() . '/' . $endpoint. $signature
        );
        } catch (\Exception $exception) {
            $this->logError("Error Retrieving Resource from $endpoint:\n". json_encode($original));
            throw new AdapterException(json_encode($original), 0, $exception);
        }

        if ($response->getStatusCode() === 200) {
            $this->logDebug('Response Code received: ' . $response->getStatusCode());
            $this->logDebug('Returning decoded response contents.');
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new TransactionException($response);
    }

    /**
     * @inheritdoc
     */
    public function sign(string $data)
    {
        return hash_hmac('sha256', $data, $this->credentials->secret);
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