<?php

namespace Royalcms\Component\Corcel\Model;

/**
 * Class Menu
 *
 * @package Royalcms\Component\Corcel\Model
 */
class Menu extends Taxonomy
{
    /**
     * @var string
     */
    protected $taxonomy = 'nav_menu';

    /**
     * @var array
     */
    protected $with = ['term', 'items'];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(
            MenuItem::class, 'term_relationships', 'term_taxonomy_id', 'object_id'
        )->orderBy('menu_order');
    }
}
