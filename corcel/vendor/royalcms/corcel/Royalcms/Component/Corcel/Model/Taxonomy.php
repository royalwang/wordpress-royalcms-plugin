<?php

namespace Royalcms\Component\Corcel\Model;

use Royalcms\Component\Corcel\Model;
use Royalcms\Component\Corcel\Model\Builder\TaxonomyBuilder;
use Royalcms\Component\Corcel\Model\Meta\TermMeta;

/**
 * Class Taxonomy
 *
 * @package Royalcms\Component\Corcel\Model
 */
class Taxonomy extends Model
{
    /**
     * @var string
     */
    protected $table = 'term_taxonomy';

    /**
     * @var string
     */
    protected $primaryKey = 'term_taxonomy_id';

    /**
     * @var array
     */
    protected $with = ['term', 'posts'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany(TermMeta::class, 'term_id');
    }
    
    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Taxonomy::class, 'parent');
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(
            Post::class, 'term_relationships', 'term_taxonomy_id', 'object_id'
        );
    }

    /**
     * @param \Royalcms\Component\Database\Query\Builder $query
     * @return TaxonomyBuilder
     */
    public function newEloquentBuilder($query)
    {
        $builder = new TaxonomyBuilder($query);

        return isset($this->taxonomy) && $this->taxonomy ?
            $builder->where('taxonomy', $this->taxonomy) :
            $builder;
    }

    /**
     * Magic method to return the meta data like the post original fields.
     *
     * @param string $key
     * @return string
     */
    public function __get($key)
    {
        if (!isset($this->$key)) {
            if (isset($this->term->$key)) {
                return $this->term->$key;
            }
        }

        return parent::__get($key);
    }
}
