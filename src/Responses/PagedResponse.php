<?php

namespace DarkGhostHunter\FlowSdk\Responses;

use DarkGhostHunter\FlowSdk\Helpers\Fluent;

/**
 * Class PagedResponse
 * @package DarkGhostHunter\FlowSdk
 *
 * @property-read array $items
 * @property-read int $total
 * @property-read int $page
 * @property-read bool $hasMore
 */
class PagedResponse extends Fluent
{

    /**
     * Total of resources in the Service
     *
     * @var int
     */
    protected $total;

    /**
     * Page retrieved
     *
     * @var int
     */
    protected $page;

    /**
     * If there is more items to retrieve in the next page.
     *
     * @var bool
     */
    protected $hasMore;


    /**
     * Sets the Items attribute
     *
     * @param $value
     */
    public function setItemsAttribute($value)
    {
        $this->attributes = $value;
    }

    /**
     * Gets the Items attribute
     *
     * @return array
     */
    public function getItemsAttribute()
    {
        return $this->attributes;
    }

    /**
     * Gets the Page attribute
     *
     * @param $value
     */
    public function setPageAttribute($value)
    {
        $this->page = $value;
    }

    /**
     * Sets the Page attribute
     *
     * @return int
     */
    public function getPageAttribute()
    {
        return $this->page;
    }

    /**
     * Gets the Total attribute
     *
     * @param $value
     */
    public function setTotalAttribute($value)
    {
        $this->total = $value;
    }

    /**
     * Sets the Total attribute
     *
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->total;
    }

    /**
     * Gets the HasMore attribute
     *
     * @param $value
     */
    public function setHasMoreAttribute($value)
    {
        $this->hasMore = !!$value;
    }

    /**
     * Sets the HasMore attribute
     *
     * @return bool
     */
    public function getHasMoreAttribute()
    {
        return $this->hasMore;
    }
}