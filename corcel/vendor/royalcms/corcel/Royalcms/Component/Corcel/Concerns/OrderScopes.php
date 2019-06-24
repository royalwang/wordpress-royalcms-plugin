<?php

namespace Royalcms\Component\Corcel\Concerns;

use Royalcms\Component\Database\Eloquent\Builder;

/**
 * Trait OrderedTrait
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait OrderScopes
{
    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNewest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOldest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'asc');
    }
}
