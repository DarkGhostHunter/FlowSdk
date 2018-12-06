<?php

namespace DarkGhostHunter\FlowSdk\Contracts;

use DarkGhostHunter\FlowSdk\Flow;

interface ServiceInterface
{

    /**
     * ServiceInterface constructor.
     *
     * @param Flow $flow
     */
    public function __construct(Flow $flow);

    /**
     * Return the base Endpoint
     *
     * @return mixed
     */
    public function getEndpoint();

    /**
     * Sets the base Endpoint
     *
     * @param string $endpoint
     * @return mixed
     */
    public function setEndpoint(string $endpoint);

    /**
     * Get the map for every action in the resource API
     *
     * @return mixed
     */
    public function getVerbsMap();

    /**
     * Set the map for every action in the resource API
     *
     * @param array $map
     * @return void
     */
    public function setVerbsMap(array $map);

    /**
     * Get the editable attributes of the Service Resource
     *
     * @return array
     */
    public function getEditableAttributes();

    /**
     * Set the editable attributes for the Service Resource
     *
     * @param array $editable
     */
    public function setEditableAttributes(array $editable);

    /**
     * Returns if an Method can be performed in the Service
     *
     * @param string $method
     * @return bool|mixed
     */
    public function can(string $method);

    /**
     * Makes a Resource for the Service (it doesn't persist it)
     *
     * @param array $attributes
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     */
    public function make(array $attributes);

}