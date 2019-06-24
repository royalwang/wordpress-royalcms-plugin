<?php

namespace Royalcms\Component\Corcel\Traits;

use Royalcms\Component\Support\Arr;

/**
 * Trait AliasesTrait
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait AliasesTrait
{
    /**
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if ($value === null && count(static::getAliases())) {
            if ($value = Arr::get(static::getAliases(), $key)) {
                if (is_array($value)) {
                    $meta = Arr::get($value, 'meta');

                    return $meta ? $this->meta->$meta : null;
                }

                return parent::getAttribute($value);
            }
        }

        return $value;
    }

    /**
     * @return array
     */
    public static function getAliases()
    {
        if (isset(parent::$aliases) && count(parent::$aliases)) {
            return array_merge(parent::$aliases, static::$aliases);
        }

        return static::$aliases;
    }

    /**
     * @param string $new
     * @param string $old
     */
    public static function addAlias($new, $old)
    {
        static::$aliases[$new] = $old;
    }
}
