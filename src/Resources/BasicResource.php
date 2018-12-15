<?php

namespace DarkGhostHunter\FlowSdk\Resources;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Contracts\ServiceInterface;
use DarkGhostHunter\FlowSdk\Helpers\Fluent;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;
use DarkGhostHunter\FlowSdk\Services\BaseService;

class BasicResource extends Fluent implements ResourceInterface
{

    /**
     * Service using this Resource
     *
     * @var BaseService
     */
    protected $service;

    /**
     * Type of Resource
     *
     * @var string
     */
    protected $type;

    /**
     * Determines if the Resource exists for modifying operations
     *
     * @var
     */
    protected $exists = false;

    /**
     * Flow BasicResponse for commitment
     *
     * @var \DarkGhostHunter\FlowSdk\Responses\BasicResponse
     */
    protected $response;

    /**
     * If the Resource can only be committed (not created raw)
     *
     * @var bool
     */
    protected $committable = false;


    /**
     * Editable attributes this Service Resources
     *
     * @var array
     */
    protected $editable = [];

    /*
    |--------------------------------------------------------------------------
    | Booting
    |--------------------------------------------------------------------------
    */

    /**
     * ResourceInterface constructor.
     *
     * @param array $attributes
     * @throws \Exception
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->boot();
    }

    /**
     * Boot the resource
     *
     * @return void
     */
    protected function boot()
    {
        $this->bootExistence();
    }

    /**
     * Boot the existence of the Resource
     *
     * @return void
     */
    protected function bootExistence()
    {
        $this->exists = $this->attributes['status'] ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Getters and Setters
    |--------------------------------------------------------------------------
    */

    /**
     * Gets the Service used by this Resource
     *
     * @return ServiceInterface
     * @return \DarkGhostHunter\FlowSdk\Services\BaseService
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Sets the Service used by this Resource
     *
     * @param ServiceInterface $service
     * @return void
     */
    public function setService(ServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Get the Resource Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the Resource Type
     *
     * @param string $type
     * @return void
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * Return if the Resource exists in Flow or has been soft-deleted
     *
     * @return bool
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * Set if the Resource exists in Flow or has been soft-deleted
     *
     * @param bool $exists
     * @return void
     */
    public function setExists(bool $exists = true)
    {
        $this->exists = $exists;
    }

    /**
     * Returns the saved BasicResponse from Flow commitment
     *
     * @return BasicResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets the BasicResponse in this resource
     *
     * @param $response
     * @return void
     */
    public function setResponse(BasicResponse $response)
    {
        $this->response = $response;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the ID Key for the Resource
     *
     * @return string
     */
    public function getIdKey()
    {
        return $this->service->getId();
    }

    /**
     * Returns the ID value for this Resource
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->attributes[$this->service->getId()] ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function commit()
    {
        if (!$this->exists && !$this->response) {
            return $this->response = $this->service->commit($this->toArray());
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function refresh()
    {
        if ($this->exists) {
            if ($refreshed = $this->service->get($this->getIdKey(), $this->getId())) {
                $this->setAttributes(
                    $refreshed->toArray()
                );
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function save()
    {
        // Try to save it first.
        if ($this->exists) {
            if ($updated = $this->service->update($this->getId(), $this->toArray())) {
                $this->setAttributes($updated->toArray());
                return true;
            }

        }

        // It doesn't exist, so try to create it.
        $created = $this->service->create($this->toArray());

        $this->setAttributes($created->toArray());

        return $this->exists = true;
    }

    /**
     * Deletes the resource from Flow
     *
     * @return bool
     */
    public function delete()
    {
        if ($this->exists && $deleted = $this->service->delete($this->getId())) {
            $this->setAttributes($deleted->toArray());
            return !$this->exists = false;
        }
        return false;
    }

}