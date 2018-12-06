<?php

namespace DarkGhostHunter\FlowSdk\Services;

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

    /*
    |--------------------------------------------------------------------------
    | Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Cancels an Invoice
     *
     * @param string $id
     * @return array
     */
    public function cancel(string $id)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Cancelling Invoice $id");

        return $this->flow->getAdapter()->post(
            $this->endpoint . '/cancel',
            [
                $this->id => $id,
            ]
        );
    }


}