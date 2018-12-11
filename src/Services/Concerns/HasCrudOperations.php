<?php

namespace DarkGhostHunter\FlowSdk\Services\Concerns;

use DarkGhostHunter\FlowSdk\Responses\BasicResponse;

/**
 * Trait HasCrudOperations
 * @package DarkGhostHunter\FlowSdk\Services\Concerns
 */
trait HasCrudOperations
{

    /*
    |--------------------------------------------------------------------------
    | C-CRUD Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Commits the transaction into Flow, and returns a Response
     *
     * @param array $attributes
     * @return \DarkGhostHunter\FlowSdk\Helpers\Fluent|BasicResponse
     * @throws \Exception
     */
    public function commit(array $attributes)
    {
        if ($this->can('commit')) {

            $this->flow->getLogger()->debug('Committing Resource: ' . json_encode($attributes));

            return BasicResponse::make(
                $this->performCommit($attributes)
            );
        }
        throw new \BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the Commit action with the Flow Adapter
     *
     * @param array $attributes
     * @return array
     */
    protected function performCommit(array $attributes)
    {
        $method = 'create';

        if (isset($this->verbsMap['commit'])) {
            $method = $this->verbsMap['commit'];
        }

        return $this->flow->send(
            'post',
            $this->endpoint . '/' . $method,
            $attributes
        );
    }

    /**
     * Creates a Resource
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        if ($this->can('create')) {

            $this->flow->getLogger()->debug('Creating Resource: ' . json_encode($attributes));

            return $this->make(
                $this->performCreate($attributes)
            );
        }
        throw new \BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the Creation of the the Resource
     *
     * @param array $attributes
     * @param array|null $options
     * @return mixed
     */
    protected function performCreate(array $attributes, array $options = null)
    {
        $method = 'create';

        if (isset($options['method'])) {
            $method = $options['method'];
        } elseif (isset($this->verbsMap['create'])) {
            $method = $this->verbsMap['create'];
        }

        return $this->flow->send(
            'post',
            $this->endpoint . '/' . $method,
            $attributes
        );
    }

    /**
     * Gets the Resource
     *
     * @param string $id
     * @param null $options
     * @return mixed
     */
    public function get($id, $options = null)
    {
        if ($this->can('get')) {
            $this->flow->getLogger()->debug("Retrieving Resource: $this->id => $id");
            return $this->make(
                $this->performGet($this->id, $id)
            );
        }
        throw new \BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the retrieval of the resource
     *
     * @param string $key
     * @param string $id
     * @param array|null $options
     * @return array
     */
    protected function performGet($key, $id, array $options = null)
    {
        $method = 'get';

        if (isset($options['method'])) {
            $method = $options['method'];
        } elseif (isset($this->verbsMap['get'])) {
            $method = $this->verbsMap['get'];
        }

        return $this->flow->send(
            'get',
            $this->endpoint . '/' . $method,
            [$key => $id]
        );
    }

    /**
     * Updates a Resource
     *
     * @param $id
     * @param mixed ...$attributes
     * @return mixed
     */
    public function update($id, ...$attributes)
    {
        if ($this->can('update')) {

            $this->flow->getLogger()->debug("Updating Resource Id: $this->id => $id");

            $attributes = count($attributes) === 1 && is_array($attributes[0])
                ? $attributes[0]
                : $attributes;

            $attributes = $this->editableAttributes
                ? array_intersect_key($attributes, array_flip($this->editableAttributes))
                : $attributes;

            return $this->make(
                $this->performUpdate(
                    array_merge($attributes, [$this->getId() => $id])
                )
            );
        }
        throw new \BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the Update of the resource
     *
     * @param array $attributes
     * @param array|null $options
     * @return array
     */
    protected function performUpdate(array $attributes, array $options = null)
    {
        $method = 'edit';

        if (isset($options['method'])) {
            $method = $options['method'];
        } elseif (isset($this->verbsMap['update'])) {
            $method = $this->verbsMap['update'];
        }

        return $this->flow->send(
            'post',
            $this->endpoint . '/' . $method,
            $attributes
        );
    }

    /**
     * Deletes a Resource
     *
     * @param string $id
     * @return mixed
     */
    public function delete($id)
    {
        if ($this->can('delete')) {

            $this->flow->getLogger()->debug("Deleting Resource: $this->id => $id");

            return $this->make(
                $this->performDelete($this->id, $id)
            );
        }
        throw new \BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the Delete action with the Flow Adapter
     *
     * @param string $key
     * @param string $id
     * @param array|null $options
     * @return array
     */
    protected function performDelete($key, $id, array $options = null)
    {

        $method = 'delete';

        if (isset($options['method'])) {
            $method = $options['method'];
        } elseif (isset($this->verbsMap['delete'])) {
            $method = $this->verbsMap['delete'];
        }

        return $this->flow->send(
            'post',
            $this->endpoint . '/' . $method,
            [$key => $id]
        );
    }
}