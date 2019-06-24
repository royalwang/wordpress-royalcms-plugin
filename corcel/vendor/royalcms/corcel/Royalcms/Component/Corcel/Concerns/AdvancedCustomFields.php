<?php

namespace Royalcms\Component\Corcel\Concerns;

use Royalcms\Component\Corcel\Acf\AdvancedCustomFields as BaseAdvancedCustomFields;

/**
 * Trait HasAcfFields
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait AdvancedCustomFields
{
    /**
     * @return AdvancedCustomFields
     */
    public function getAcfAttribute()
    {
        return new BaseAdvancedCustomFields($this);
    }
}
