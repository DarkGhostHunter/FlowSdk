<?php

namespace DarkGhostHunter\FlowSdk\Services\Concerns;

use BadMethodCallException;
use DarkGhostHunter\FlowSdk\Responses\BasicResponse;

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
     * @return \DarkGhostHunter\Fluid\Fluid|BasicResponse
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
        return $this->flow->send(
            'post',
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['commit'] ?? 'create'),
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
        throw new BadMethodCallException('Method '.__FUNCTION__.' does not exist');
    }

    /**
     * Performs the Creation of the the Resource
     *
     * @param array $attributes
     * @param array|null $options
     * @return array
     */
    protected function performCreate(array $attributes, array $options = null)
    {
        return $this->flow->send(
            'post',
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['create'] ?? 'create'),
            $attributes
        );
    }

    /**
     * Gets the Resource
     *
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
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
    protected function performGet(string $key, string $id, array $options = null)
    {
        return $this->flow->send(
            'get',
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['get'] ?? 'get'),
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
    public function update($id, array $attributes)
    {
        if ($this->can('update')) {

            $this->flow->getLogger()->debug("Updating Resource Id: $this->id => $id");

            $attributes = $this->updateableAttributes
                ? array_intersect_key($attributes, array_flip($this->updateableAttributes))
                : $attributes;

            return $this->make(
                $this->performUpdate(
                    array_merge($attributes, [$this->id => $id])
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
        return $this->flow->send(
            'post',
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['update'] ?? 'edit'),
            $attributes
        );
    }

    /**
     * Deletes a Resource
     *
     * @param string $id
     * @return mixed
     */
    public function delete(string $id)
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
    protected function performDelete(string $key, string $id, array $options = null)
    {
        return $this->flow->send(
            'post',
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['delete'] ?? 'delete'),
            [$key => $id]
        );
    }
}