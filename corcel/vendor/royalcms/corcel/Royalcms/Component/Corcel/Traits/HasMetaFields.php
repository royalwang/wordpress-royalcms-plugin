<?php

namespace Royalcms\Component\Corcel\Traits;

use Royalcms\Component\Corcel\Model\Attachment;
use Royalcms\Component\Corcel\Model\CustomLink;
use Royalcms\Component\Corcel\Model\MenuItem;
use Royalcms\Component\Corcel\Model\Meta\PostMeta;
use Royalcms\Component\Corcel\Model\Page;
use Royalcms\Component\Corcel\Model\Post;
use Royalcms\Component\Database\Eloquent\Builder;
use Royalcms\Component\Support\Arr;
use ReflectionClass;

/**
 * Trait HasMetaFields
 *
 * @package Royalcms\Component\Corcel\Traits
 */
trait HasMetaFields
{
    /**
     * @var array
     */
    private $customMetaClasses = [
        \Royalcms\Component\Corcel\Model\Comment::class,
        \Royalcms\Component\Corcel\Model\Term::class,
        \Royalcms\Component\Corcel\Model\User::class,
    ];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany(
            $this->getClassName(), $this->getFieldName()
        );
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->meta();
    }

    /**
     * @param Builder $query
     * @param string $meta
     * @param mixed $value
     * @return Builder
     */
    public function scopeHasMeta(Builder $query, $meta, $value = null)
    {
        if (!is_array($meta)) {
            $meta = [$meta => $value];
        }

        foreach($meta as $key => $value) {
            $query->whereHas('meta', function ($query) use ($key, $value) {
                if (is_string($key)) {
                    $query->where('meta_key', $key);

                    return is_null($value) ? $query : // 'foo' => null
                        $query->where('meta_value', $value); // 'foo' => 'bar'
                }

                return $query->where('meta_key', $value); // 0 => 'foo'
            });
        }

        return $query;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function saveMeta($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->saveOneMeta($k, $v);
            }

            $this->load('meta');

            return true;
        }

        return $this->saveOneMeta($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    private function saveOneMeta($key, $value)
    {
        $meta = $this->meta()->where('meta_key', $key)
            ->firstOrNew(['meta_key' => $key]);

        $result = $meta->fill(['meta_value' => $value])->save();

        $this->load('meta');

        return $result;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function saveField($key, $value)
    {
        return $this->saveMeta($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \Royalcms\Component\Database\Eloquent\Model|\Royalcms\Component\Support\Collection
     */
    public function createMeta($key, $value = null)
    {
        if (is_array($key)) {
            return collect($key)->map(function ($value, $key) {
                return $this->createOneMeta($key, $value);
            });
        }

        return $this->createOneMeta($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \Royalcms\Component\Database\Eloquent\Model
     */
    private function createOneMeta($key, $value)
    {
        $meta =  $this->meta()->create([
            'meta_key' => $key,
            'meta_value' => $value,
        ]);

        $this->load('meta');

        return $meta;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return \Royalcms\Component\Database\Eloquent\Model
     */
    public function createField($key, $value)
    {
        return $this->createMeta($key, $value);
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        $className = sprintf(
            'Royalcms\\Component\\Corcel\\Model\\Meta\\%sMeta', $this->getCallerClassName()
        );

        return class_exists($className) ?
            $className :
            PostMeta::class;
    }

    /**
     * @return string
     */
    private function getFieldName()
    {
        $callerName = $this->getCallerClassName();

        return sprintf('%s_id', strtolower($callerName));
    }

    /**
     * @return string
     */
    private function getCallerClassName()
    {
        $class = static::class;

        if (!in_array($class, $this->customMetaClasses)) {
            $class = Post::class;
        }

        return (new ReflectionClass($class))->getShortName();
    }
}
