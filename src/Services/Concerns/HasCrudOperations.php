<?php

namespace DarkGhostHunter\FlowSdk\Services\Concerns;

use DarkGhostHunter\FlowSdk\Contracts\ResourceInterface;
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
     * @inheritdoc
     */
    public function commit(array $attributes)
    {
        if ($this->can('commit')) {

            $this->flow->getLogger()->debug('Commiting Resource: ' . json_encode($attributes));

            return BasicResponse::make(
                $this->performCommit($attributes)
            );
        }
        return false;
    }

    /**
     * Performs the Commit action with the Flow Adapter
     *
     * @param array $attributes
     * @param null $options
     * @return array
     */
    protected function performCommit(array $attributes, $options = null)
    {
        return $this->flow->getAdapter()->post(
            $this->endpoint . '/' . $options['method'] ?? $this->verbsMap['commit'] ?? 'create',
            $attributes,
            $options
        );
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function create(array $attributes)
    {
        if ($this->can('create')) {

            $this->flow->getLogger()->debug('Creating Resource: ' . json_encode($attributes));

            return $this->make(
                $this->performCreate($attributes)
            );
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    protected function performCreate(array $attributes, array $options = null)
    {
        return $this->flow->getAdapter()->post(
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['create'] ?? 'create'),
            $attributes
        );
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function get(string $id, $options = null)
    {
        if ($this->can('get')) {
            $this->flow->getLogger()->debug("Retrieving Resource: $this->id => $id");
            return $this->make(
                $this->performGet($this->id, $id)
            );
        }
        return false;
    }

    /**
     * Performs the Retrieve action with the Flow Adapter
     *
     * @param string $key
     * @param string $id
     * @param array|null $options
     * @return array
     */
    protected function performGet(string $key, string $id, array $options = null)
    {
        return $this->flow->getAdapter()->get(
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['get'] ?? 'get'),
            [$key => $id]
        );
    }

    /**
     * @inheritdoc
     */
    public function update($id, ...$attributes)
    {
        if ($this->can('update')) {

            $this->flow->getLogger()->debug("Updating Resource Id: $this->id => $id");

            return $this->make(
                $this->performUpdate($attributes)
            );
        }
        return false;
    }

    /**
     * Performs the Update action with the Flow Adapter
     *
     * @param array $attributes
     * @param array|null $options
     * @return array
     */
    protected function performUpdate(array $attributes, array $options = null)
    {
        return $this->flow->getAdapter()->post(
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['update'] ?? 'edit'),
            $this->editableAttributes
                ? array_intersect_key(array_flip($this->editableAttributes), $attributes)
                : $attributes
        );
    }

    /**
     * @inheritdoc
     */
    public function delete(string $id)
    {
        if ($this->can('delete')) {

            $this->flow->getLogger()->debug("Deleting Resource: $this->id => $id");

            return $this->make(
                $this->performDelete($this->id, $id)
            );
        }
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
        return $this->flow->getAdapter()->post(
            $this->endpoint . '/' . ($options['method'] ?? $this->verbsMap['delete'] ?? 'delete'),
            [$key => $id]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Convenience Operations
    |--------------------------------------------------------------------------
    */

    /**
     * Makes and Commits immediately a Resource
     *
     * @param array $attributes
     * @return ResourceInterface
     * @throws \Exception
     */
    public function makeAndCommit(array $attributes)
    {
        $resource = $this->make($attributes);

        $resource->commit();

        return $resource;
    }

    /**
     * Makes and Saves a Resource
     *
     * @param array $attributes
     * @return ResourceInterface
     * @throws \Exception
     */
    public function makeAndSave(array $attributes)
    {
        $resource = $this->make($attributes);

        $resource->save();

        return $resource;
    }
}