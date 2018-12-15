<?php

namespace DarkGhostHunter\FlowSdk\Contracts;

use DarkGhostHunter\FlowSdk\Responses\BasicResponse;

interface ResourceInterface
{
    /**
     * ResourceInterface constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes);

    /**
     * Gets the Service used by this Resource
     *
     * @return ServiceInterface
     * @return \DarkGhostHunter\FlowSdk\Services\BaseService
     */
    public function getService();

    /**
     * Sets the Service used by this Resource
     *
     * @param ServiceInterface $service
     * @return void
     */
    public function setService(ServiceInterface $service);

    /**
     * Get the Resource Type
     *
     * @return string
     */
    public function getType();

    /**
     * Set the Resource Type
     *
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * Returns the saved BasicResponse from Flow commitment
     *
     * @return \DarkGhostHunter\FlowSdk\Responses\BasicResponse
     */
    public function getResponse();

    /**
     * Sets the BasicResponse in this resource
     *
     * @param BasicResponse $response
     * @return void
     */
    public function setResponse(BasicResponse $response);

    /**
     * Return if the Resource exists in Flow or has been soft-deleted
     *
     * @return bool
     */
    public function exists();

    /**
     * Set if the Resource exists in Flow or has been soft-deleted
     *
     * @param bool $exists
     * @return void
     */
    public function setExists(bool $exists);



    /**
     * Commits the resource to Flow
     *
     * @return bool
     */
    public function commit();

    /**
     * Reacquires the resource from Flow
     *
     * @return bool
     */
    public function refresh();

    /**
     * Updates the resource from Flow
     *
     * @return bool
     */
    public function save();

    /**
     * Deletes the resource from Flow
     *
     * @return bool
     */
    public function delete();

    /**
     * Transforms the resource into an Array
     *
     * @return array
     */
    public function toArray();

}