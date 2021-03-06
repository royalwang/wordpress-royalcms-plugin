<?php

namespace Royalcms\Component\Corcel\Model;

use Royalcms\Component\Support\Arr;

/**
 * Class MenuItem
 *
 * @package Royalcms\Component\Corcel\Model
 */
class MenuItem extends Post
{
    /**
     * @var string
     */
    protected $postType = 'nav_menu_item';

    /**
     * @var array
     */
    private $instanceRelations = [
        'post'      => Post::class,
        'page'      => Page::class,
        'custom'    => CustomLink::class,
        'category'  => Taxonomy::class,
    ];

    /**
     * @return Post|Page|CustomLink|Taxonomy
     */
    public function parent()
    {
        if ($className = $this->getClassName()) {
            return (new $className)->newQuery()
                ->find($this->meta->_menu_item_menu_item_parent);
        }

        return null;
    }

    /**
     * @return Post|Page|CustomLink|Taxonomy
     */
    public function instance()
    {
        if ($className = $this->getClassName()) {
            return (new $className)->newQuery()
                ->find($this->meta->_menu_item_object_id);
        }

        return null;
    }

    /**
     * @return string
     */
    private function getClassName()
    {
        return Arr::get(
            $this->instanceRelations, $this->meta->_menu_item_object
        );
    }
}
