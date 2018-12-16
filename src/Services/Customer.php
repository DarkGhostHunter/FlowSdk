<?php

namespace DarkGhostHunter\FlowSdk\Services;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
use DarkGhostHunter\FlowSdk\Resources\BasicResource;
use DarkGhostHunter\FlowSdk\Resources\CustomerResource;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;

/**
 * Class Customer
 * @package DarkGhostHunter\FlowSdk\Services
 *
 * @method CustomerResource create(array $attributes)
 * @method CustomerResource get(string $id, $options = null)
 * @method CustomerResource update($id, ...$attributes)
 * @method CustomerResource delete(string $id)
 *
 */
class Customer extends BaseService
{
    use Concerns\HasCrudOperations,
        Concerns\HasPagination;

    /**
     * Main identifier
     *
     * @var string
     */
    protected $id = 'customerId';

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

    /**
     * Resource Class to instantiate
     *
     * @var string
     */
    protected $resourceClass = CustomerResource::class;

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
        // A customer is active (1) or is deleted.
        return $resource->get('status') === 1;
    }

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
            $this->flow->send('post',
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
     * @return BasicResource & CustomerResource
     * @throws \Exception
     */
    public function getCard(string $token)
    {
        // Lof Debug
        $this->flow->getLogger()->debug("Retrieving Card with token $token");

        $resource = $this->make(
            $this->flow->send(
                'get',
                $this->endpoint . '/getRegisterStatus',
                ['token' => $token]
            )
        );

        $resource->setExists((int)$resource->status === 1);

        return $resource;
    }

    /**
     * Unregisters a Credit Card from the Customer
     *
     * @param string $customerId
     * @return BasicResource & CustomerResource
     * @throws \Exception
     */
    public function unregisterCard(string $customerId)
    {
        // Log Debug
        $this->flow->getLogger()->debug("Unregistering Card for $customerId");

        return $this->make(
            $this->flow->send(
                'post',
                $this->endpoint . '/unRegister',
                ['customerId' => $customerId]
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
        $resource = new BasicResource($attributes);

        $resource->setType('charge');

        $resource->setExists(in_array($resource->status, [1,2]));

        return $resource;
    }

    /**
     * Immediately charges a desired amount into the customer registered
     * Credit Card
     *
     * @param array $attributes
     * @return BasicResource
     * @throws \Exception
     */
    public function createCharge(array $attributes)
    {
        // Log Debug
        $this->flow->getLogger()->debug('Charging: ' . json_encode($attributes));

        return $this->makeCharge(
            $this->flow->send(
                'post',
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
            $this->flow->send(
                'post',
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
            array_merge($options ?? [], [
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