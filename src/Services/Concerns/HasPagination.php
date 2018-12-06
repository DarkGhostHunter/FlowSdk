<?php

namespace DarkGhostHunter\FlowSdk\Services\Concerns;

use DarkGhostHunter\FlowSdk\Responses\PagedResponse;

/**
 * Trait HasPagination
 * @package DarkGhostHunter\FlowSdk\Services\Concerns
 *
 * @mixin \DarkGhostHunter\FlowSdk\Services\BaseService
 */
trait HasPagination
{
    /**
     * Resources to get per page
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Gets a Page of Resources from the Service
     *
     * @param int $page
     * @param array|null $options
     * @return PagedResponse
     * @throws \Exception
     */
    public function getPage(int $page, array $options = null)
    {
        $params = [
            'start' => ($page * $this->perPage) - $this->perPage,
            'limit' => $this->perPage ?? 10,
        ];

        foreach ($options ?? [] as $key => $option) {
            if ($key !== 'method') {
                $params[$key] = $option;
            }
        }

        // Log Debug
        $this->flow->getLogger()->debug("Retrieving Resource List:" .
            lcfirst(substr(strrchr(get_class($this), '\\'), 1))
            . "$page, $this->id"
        );

        // Get the BasicResponse from the Adapter
        $response = $this->flow->getAdapter()->get(
            $this->endpoint . '/' . ($options['method'] ?? $this->paginationMethod ?? 'list'),
            $params
        );

        $items = [];

        // For each item in the `data` key, transform it as this Service Resource or the class
        foreach ($response['data'] ?? [] as $key => $item) {
            $item = $this->make($item);
            $items[$params['start'] + $key] = $item;
        }

        // Becauze Zend Engine uses copy-on-write, we didn't use foreach by reference
        $response['items'] = $items;
        unset($response['data']);

        // Return the paged BasicResponse
        return (new PagedResponse($response))->page($page);
    }

    /**
     * Get the items to retrieve per page
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Sets the items to retrieve per page
     *
     * @param int $perPage
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
    }

}