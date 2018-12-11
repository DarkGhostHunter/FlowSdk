<?php

namespace DarkGhostHunter\FlowSdk\Resources;

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
     * @return bool
     */
    public function registerCard($urlReturn = null)
    {
        if ($this->exists) {
            return $this->service->registerCard($this->getId(), $urlReturn);
        }
        return false;
    }

    /**
     * Removes the Registered Credit Card for this Customer
     *
     * @return mixed
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
     * @return bool
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
     * @return mixed
     */
    public function reverseCharge($idType, $value)
    {
        if ($this->exists) {
            return $this->service->reverseCharge(
                $idType !== null
                    ? $idType : 'customerOrder'
                , $value
            );
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
    public function getChargesPage($page, array $options = null)
    {
        if ($this->exists) {
            return $this->service->getChargesPage($this->getId(), $page, $options);
        }
        return false;
    }
}