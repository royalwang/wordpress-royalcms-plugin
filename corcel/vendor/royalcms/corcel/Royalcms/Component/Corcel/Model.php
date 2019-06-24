<?php

namespace Royalcms\Component\Corcel;

use Royalcms\Component\Database\Eloquent\Collection;
use Royalcms\Component\Database\Eloquent\Model as Eloquent;
use Royalcms\Component\Database\Eloquent\Relations\HasMany;
use Royalcms\Component\Database\Eloquent\Relations\HasOne;
use Royalcms\Component\Database\Eloquent\Relations\BelongsTo;
use Royalcms\Component\Database\Eloquent\Relations\BelongsToMany;
use Royalcms\Component\Support\Str;

/**
 * Class Model
 *
 * @package Royalcms\Component\Corcel
 */
class Model extends Eloquent
{
    /**
     * @var string
     */
    protected $postType;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->configureDatabaseConnection();
        parent::__construct($attributes);
    }


    /**
     * Replace the original hasMany function to forward the connection name.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $localKey
     * @return HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related();
        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $localKey = $localKey ?: $this->getKeyName();

        return new HasMany($instance->newQuery(), $this, $foreignKey, $localKey);
    }

    /**
     * Replace the original hasOne function to forward the connection name.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $localKey
     * @return HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related();
        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $localKey = $localKey ?: $this->getKeyName();

        return new HasOne($instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey);
    }

    /**
     * Replace the original belongsTo function to forward the connection name.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $otherKey
     * @param string $relation
     * @return BelongsTo
     */
    public function belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
    {
        if (is_null($relation)) {
            list(, $caller) = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $relation = $caller['function'];
        }

        if (is_null($foreignKey)) {
            $foreignKey = Str::snake($relation).'_id';
        }

        $instance = new $related();
        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $query = $instance->newQuery();

        $otherKey = $otherKey ?: $instance->getKeyName();

        return new BelongsTo($query, $this, $foreignKey, $otherKey, $relation);
    }

    /**
     * Replace the original belongsToMany function to forward the connection name.
     *
     * @param string $related
     * @param string $table
     * @param string $foreignKey
     * @param string $otherKey
     * @param string $relation
     * @return BelongsToMany
     */
    public function belongsToMany($related, $table = null, $foreignKey = null, $otherKey = null, $relation = null)
    {
        if (is_null($relation)) {
            $relation = $this->getRelations();
        }

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related();
        if ($instance instanceof self) {
            $instance->setConnection($this->getConnection()->getName());
        } else {
            $instance->setConnection($instance->getConnection()->getName());
        }

        $otherKey = $otherKey ?: $instance->getForeignKey();

        if (is_null($table)) {
            $table = $this->joiningTable($related);
        }

        $query = $instance->newQuery();

        return new BelongsToMany($query, $this, $table, $foreignKey, $otherKey, $relation);
    }

    /**
     * Get the relation value setting the connection name.
     *
     * @param string $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        $relation = parent::getRelationValue($key);

        if ($relation instanceof Collection) {
            $relation->each(function ($model) {
                $this->setRelationConnection($model);
            });

            return $relation;
        }

        $this->setRelationConnection($relation);

        return $relation;
    }

    /**
     * Set the connection name to model.
     *
     * @param $model
     */
    protected function setRelationConnection($model)
    {
        if ($model instanceof Eloquent) {
            $model->setConnection($this->getConnectionName());
        }
    }

    /**
     * @return \Royalcms\Component\Database\Connection|string
     */
    public function getConnectionName()
    {
        if (null === $this->connection) {
            return $this->getConnection()->getName();
        }

        return $this->connection;
    }

    /**
     * @return void
     */
    private function configureDatabaseConnection()
    {
        if (!isset($this->connection)) {
            if ($connection = config('corcel.connection')) {
                $this->connection = $connection;
            }
        }
    }
}
