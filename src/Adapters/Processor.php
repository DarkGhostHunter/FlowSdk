<?php

namespace DarkGhostHunter\FlowSdk\Adapters;

use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;

class Processor
{
    /** @var Fluent */
    protected $flow;

    /** @var array */
    protected $data;

    /** @var string */
    protected $signable;

    /**
     * AdapterProcessor constructor.
     *
     * @param Flow $flow
     */
    public function __construct(Flow $flow)
    {
        $this->flow = $flow;
    }

    /**
     * Prepare the data
     *
     * @param string $method
     * @param array $parameters
     * @return array|string
     */
    public function prepare(string $method, array $parameters)
    {
        // Delete any empty key
        $parameters = $this->deleteEmptyKeys($parameters);

        // Add the API Key
        $parameters['apiKey'] = $this->flow->getCredentials()->apiKey;

        // Sort the parameters
        ksort($parameters);

        // Return the final data
        switch ($method) {
            case 'get':
                return $this->prepareGet($parameters);
            case 'post':
                return $this->preparePost($parameters);
            default:
                return null;
        }
    }

    /**
     * Prepares a GET Request parameters
     *
     * @param array $parameters
     * @return string
     */
    protected function prepareGet(array $parameters)
    {
        // Make a signable string
        $signable = http_build_query($parameters, null, '&');

        // Return the URL with the parameters
        return '?' . $signable . '&s=' . $this->makeSignature($signable);
    }

    /**
     * Prepares a POST Request data
     *
     * @param array $parameters
     * @return array
     */
    protected function preparePost(array $parameters)
    {
        // Transform any array value to JSON
        extract($this->parseValues($parameters));
        /** @var string $signable */

        // Add the signed string
        $parameters['s'] = $this->makeSignature($signable);

        return $parameters;
    }

    /**
     * Removes any empty or null key
     *
     * @param array $array
     * @return array
     */
    protected function deleteEmptyKeys(array $array)
    {
        return array_filter($array, function ($value) {
            return !empty($value) || is_numeric($value);
        });
    }

    /**
     * Parse the Attributes values
     *
     * @param array $parameters
     * @return array
     */
    protected function parseValues(array $parameters)
    {
        $signable = [];

        foreach ($parameters as $key => &$parameter) {

            // JSON an array value
            $parameter = $this->arrayToJson($parameter);

            // Append a secret to the present webhooks
            $parameter = $this->appendSecretToWebhook($key, $parameter);

            // Put the value string to the signable data
            $signable[] = $key . '=' . (string)$parameter;
        }

        $signable = implode('&', $signable);

        return compact('parameters', 'signable');
    }

    /**
     * Transforms an array value to json if it is an array
     *
     * @param $value
     * @return false|string
     */
    protected function arrayToJson($value)
    {
        return is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Append a secret to the Webhook URL
     *
     * @param $key
     * @param $value
     * @return string
     */
    protected function appendSecretToWebhook($key, $value)
    {
        if (($webhook = $this->flow->getWebhookSecret())
            && in_array($key, ['urlConfirmation', 'urlCallBack', 'urlCallback'])
            && !strpos($value, 'secret=')) {

            return $value . (strpos($value, '?') ? '&' : '?') . 'secret=' . $webhook;
        }

        return $value;
    }

    /**
     * Creates a signature from the signable string
     *
     * @param string $signable
     * @return string
     */
    protected function makeSignature(string $signable)
    {
        return hash_hmac('sha256', $signable, $this->flow->getCredentials()->secret);
    }
}