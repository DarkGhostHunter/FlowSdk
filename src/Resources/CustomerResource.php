<?php

namespace DarkGhostHunter\FlowSdk\Resources;

/**
 * Class CustomerResource
 * @package DarkGhostHunter\FlowSdk\Resources
 *
 * @property-read string $customerId
 * @property-read string $created
 * @property string $email
 * @property string $name
 * @property-read string $pay_mode
 * @property-read string $creditCardType
 * @property-read string $last4CardDigits
 * @property string $externalId
 * @property-read int $status
 * @property-read string $registerDate
 *
 */
class CustomerResource extends BasicResource
{

    /*
    |--------------------------------------------------------------------------
    | Additional Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Creates a petition to Register the Credit Card of this Customer
     *
     * @param string|null $urlReturn
     * @return bool|\DarkGhostHunter\FlowSdk\Responses\BasicResponse
     */
    public function registerCard(string $urlReturn = null)
    {
        if ($this->exists) {
            return $this->service->registerCard($this->getId(), $urlReturn);
        }
        return false;
    }

    /**
     * Removes the Registered Credit Card for this Customer
     *
     * @return \DarkGhostHunter\FlowSdk\Resources\BasicResource & \DarkGhostHunter\FlowSdk\Resources\CustomerResource;
     */
    public function unregisterCard()
    {
        if ($this->exists) {
            return $this->service->unregisterCard($this->getId());
        }
        return false;
    }

    /**
     * Immediately charges a desired amount into the registered Credit Card
     *
     * @param array $attributes
     * @return bool|\DarkGhostHunter\FlowSdk\Resources\BasicResource
     */
    public function charge(array $attributes)
    {
        if ($this->exists && $this->creditCardType && $this->last4CardDigits) {
            return $this->service->charge($attributes + [
                $this->getIdKey() => $this->getId()
            ]);
        }
        return false;
    }

    /**
     * Immediately reverses a previously made charge for this customer
     *
     * @param string $idType
     * @param string $value
     * @return bool|\DarkGhostHunter\FlowSdk\Responses\BasicResponse
     */
    public function reverseCharge(string $idType, string $value)
    {
        if ($this->exists) {
            return $this->service->reverseCharge($idType ?? 'customerOrder', $value);
        }
        return false;
    }

    /**
     * List the charges made to this customer
     *
     * @param string $page
     * @param array|null $options
     * @return bool|\DarkGhostHunter\FlowSdk\Responses\PagedResponse
     */
    public function getChargesPage(string $page, array $options = null)
    {
        if ($this->exists) {
            return $this->service->getChargesPage($this->getId(), $page, $options);
        }
        return false;
    }
}