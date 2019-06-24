<?php

namespace Royalcms\Component\Corcel\Model\Collection;

use Royalcms\Component\Database\Eloquent\Collection;

/**
 * Class MetaCollection
 *
 * @package Royalcms\Component\Corcel\Model\Collection
 */
class MetaCollection extends Collection
{
    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->items) && count($this->items)) {
            $meta = $this->first(function ($i, $meta) use ($key) {
                return $meta->meta_key === $key;
            });

            return $meta ? $meta->meta_value : null;
        }

        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return !is_null($this->__get($name));
    }
}
