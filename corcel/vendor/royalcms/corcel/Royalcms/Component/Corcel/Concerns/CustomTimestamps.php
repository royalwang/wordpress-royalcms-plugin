<?php

namespace Royalcms\Component\Corcel\Concerns;

/**
 * Trait CustomTimestamps
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait CustomTimestamps
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function setCreatedAt($value)
    {
        $gmt_field = static::CREATED_AT . '_gmt';
        $this->{$gmt_field} = $value;

        return parent::setCreatedAt($value);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function setUpdatedAt($value)
    {
        $gmt_field = static::UPDATED_AT . '_gmt';
        $this->{$gmt_field} = $value;

        return parent::setUpdatedAt($value);
    }
}
