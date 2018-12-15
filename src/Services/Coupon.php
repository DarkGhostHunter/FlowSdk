<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;

class Coupon extends BaseService
{
    use Concerns\HasCrudOperations,
        Concerns\HasPagination;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'couponId';

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [];

    /**
     * Update-able attributes. If null, no attributes will be filtered
     *
     * @var array|null
     */
    protected $editableAttributes = [
        'name'
    ];

    /**
     * Permitted actions of the Service Resources
     *
     * @var array
     */
    protected $permittedActions = [
        'get'    => true,
        'commit' => false,
        'create' => true,
        'update' => true,
        'delete' => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | Existence
    |--------------------------------------------------------------------------
    */

    /**
     * Calculates the Resource existence based its attributes (or presence)
     *
     * @param ResourceInterface $resource
     * @return bool
     */
    protected function calcResourceExistence(ResourceInterface $resource)
    {
        return $resource->status === 1;
    }
}