<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Resources\InvoiceResource;

class Invoice extends BaseService
{
    use Concerns\HasCrudOperations;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'invoiceId';

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [];

    /**
     * Permitted actions of the Service Resources
     *
     * @var array
     */
    protected $permittedActions = [
        'get'    => true,
        'commit' => false,
        'create' => false,
        'update' => false,
        'delete' => false,
    ];

    /**
     * Resource Class to instantiate
     *
     * @var InvoiceResource
     */
    protected $resourceClass = InvoiceResource::class;

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
        // The Invoice "doesn't exists" when its status is unpaid and "attemped" is "dont pay".
        return !((int)$resource->status === 0) && ((int)$resource->attemped === 0);
    }

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Cancels an Invoice
     *
     * @param string $id
     * @return \DarkGhostHunter\FlowSdk\Contracts\ResourceInterface|\DarkGhostHunter\FlowSdk\Resources\BasicResource|InvoiceResource
     * @throws \Exception
     */
    public function cancel(string $id)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Invoice $id");

        return $this->make(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/cancel',
                [
                    $this->id => $id,
                ]
            )
        );
    }


}