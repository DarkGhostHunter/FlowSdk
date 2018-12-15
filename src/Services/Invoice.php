<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Resources\InvoiceResource;

/**
 * Class Invoice
 * @package DarkGhostHunter\FlowSdk\Services
 *
 * @method InvoiceResource get(string $id, $options = null)
 *
 */
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
     * @var string
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
     * @param ResourceInterface & InvoiceResource $resource
     * @return bool
     */
    protected function calcResourceExistence(ResourceInterface $resource)
    {
        // It exists except when is unpaid and never to be paid.
        return ! ($resource->status === 0 && $resource->attemped === 0);
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
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource & InvoiceResource
     * @throws \Exception
     */
    public function cancel(string $id)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Invoice $id");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/cancel',
                [
                    $this->id => $id,
                ]
            )
        );
    }


}