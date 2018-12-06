<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Resources\CustomerResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;

class Customer extends BaseService
{
    use Concerns\HasPagination;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'customerId';

    /**
     * Change the default endpoint for the method for another
     *
     * @var array
     */
    protected $verbsMap = [
        'get' => 'getStatus',
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
        'update' => false,
        'delete' => false,
    ];

    /**
     * Resource Class to instantiate
     *
     * @var \DarkGhostHunter\FlowSdk\Resources\CustomerResource
     */
    protected $resourceClass = CustomerResource::class;

    /*
    |--------------------------------------------------------------------------
    | Card Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Registers a Credit Card for the Customer
     *
     * @param string $customerId
     * @param string $urlReturn
     * @return bool|BasicResponse
     * @throws \Exception
     */
    public function registerCard(string $customerId, string $urlReturn = null)
    {
        $this->flow->getLogger()->debug("Registering Card for $customerId, returns to $urlReturn");

        return BasicResponse::make(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/register',
                [
                    'customerId' => $customerId,
                    'url_return' => $urlReturn ?? $this->flow->getReturnUrls('card.url_return'),
                ]
            )
        );
    }

    /**
     * Returns the Credit Card Registration Resource Status
     *
     * @param string $token
     * @return \DarkGhostHunter\FlowSdk\Contracts\ResourceInterface|\DarkGhostHunter\FlowSdk\Resources\BasicResource|CustomerResource
     * @throws \Exception
     */
    public function getCard(string $token)
    {
        // Lof Debug
        $this->flow->getLogger()->debug("Retrieving Card with token $token");

        $resource = $this->make(
            $this->flow->getAdapter()->get(
                $this->endpoint, [
                    'token' => $token
                ]
            )
        );

        $resource->setExists($resource->status === 1);

        return $resource;
    }

    /**
     * Unregisters a Credit Card from the Customer
     *
     * @param string $customerId
     * @return \DarkGhostHunter\FlowSdk\Contracts\ResourceInterface|BasicResource|CustomerResource
     * @throws \Exception
     */
    public function unregisterCard(string $customerId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Unregistering Card for $customerId");

        return $this->make(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/unRegister',
                [
                    'customerId' => $customerId,
                ]
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Charge Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Makes a Charge
     *
     * @param array $attributes
     * @throws \Exception
     * @return BasicResource
     */
    protected function makeCharge(array $attributes)
    {
        $resource = $this->make($attributes);

        $resource->setType('charge');

        return $resource;
    }

    /**
     * Immediately charges a desired amount into the customer registered
     * Credit Card
     *
     * @param array $attributes
     * @return \DarkGhostHunter\FlowSdk\Resource|BasicResource|CustomerResource
     * @throws \Exception
     */
    public function createCharge(array $attributes)
    {
        // Log Debug
        $this->flow->getLogger()->debug('Charging: ' . json_encode($attributes));

        return $this->makeCharge(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/charge',
                $attributes
            )
        );
    }

    /**
     * Immediately reverses a previously made charge a customer
     *
     * @param string $idType
     * @param string $value
     * @return BasicResponse
     * @throws \Exception
     */
    public function reverseCharge(string $idType, string $value)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Reversing Charge: $idType, $value");

        return BasicResponse::make(
            $this->flow->getAdapter()->post(
                $this->endpoint . '/reverseCharge',
                [
                    $idType => $value
                ]
            )
        );
    }

    /**
     * List the charges made to a customer
     *
     * @param string $customerId
     * @param int $page
     * @param array|null $options
     * @return \DarkGhostHunter\FlowSdk\Responses\PagedResponse
     * @throws \Exception
     */
    public function getChargesPage(string $customerId, int $page, array $options = null)
    {
        $pagedResponse = $this->getPage(
            $page,
            array_merge($options, [
                'method' => 'getCharges',
                'customerId' => $customerId
            ])
        );

        // Get all the items from the Paged BasicResponse
        $items = $pagedResponse->items;

        // Transform each item into a Charge Resource instead of a Customer Resource
        foreach ($items as &$item) {
            $item = $this->makeCharge($item->toArray());
        }

        // Put the Charge Resources into the response
        $pagedResponse->setRawAttributes($items);

        // Profit
        return $pagedResponse;
    }


}