<?php

namespace DarkGhostHunter\FlowSdk\Contracts;

use DarkGhostHunter\FlowSdk\Flow;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;

interface AdapterInterface
{

    /**
     * AdapterInterface constructor.
     *
     * @param Flow $flow
     * @param $options
     */
    public function __construct(Flow $flow, array $options = null);

    /**
     * Return the underneath HTTP Client
     *
     * @return mixed
     */
    public function getClient();

    /**
     * Sets the HTTP Client to use with the Adapter
     *
     * @param $client
     * @return mixed
     */
    public function setClient($client);

    /**
     * Issues a HTTP POST Request to Flow API
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data) : array;

    /**
     * Issues a HTTP GET Request to Flow API
     *
     * @param string $endpoint
     * @return array
     */
    public function get(string $endpoint) : array;


    /**
     * Logs informative process
     *
     * @param string $message
     * @return void
     */
    public function logInfo(string $message);

    /**
     * Logs an Error
     *
     * @param string $message
     * @return void
     */
    public function logError(string $message);

    /**
     * Logs a Debug
     *
     * @param string $message
     * @return void
     */
    public function logDebug(string $message);

}