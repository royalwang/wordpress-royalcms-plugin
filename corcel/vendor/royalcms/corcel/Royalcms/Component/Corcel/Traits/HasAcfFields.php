<?php
namespace Royalcms\Component\Corcel\Traits;

use Royalcms\Component\Corcel\Acf\AdvancedCustomFields;

/**
 * Trait HasAcfFields
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait HasAcfFields
{
    /**
     * @return AdvancedCustomFields
     */
    public function getAcfAttribute()
    {
        return new AdvancedCustomFields($this);
    }
}
