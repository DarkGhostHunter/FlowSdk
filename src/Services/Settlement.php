<?php

namespace DarkGhostHunter\FlowSdk\Services;

class Settlement extends BaseService
{
    use Concerns\HasCrudOperations;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'id';

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
     * @param string $date
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource
     * @throws \Exception
     */
    public function getByDate(string $date)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Retrieving Settlement by Date $date");

        return $this->make(
            $this->performGet(
                'date',
                $date,
                [
                    'method' => '/getByDate',
                ]
            )
        );
    }


}