<?php

namespace Royalcms\Component\Corcel\Model;

use Royalcms\Component\Corcel\Model;

/**
 * Class TermRelationship.
 *
 * @package Royalcms\Component\Corcel\Model
 */
class TermRelationship extends Model
{
    /**
     * @var string
     */
    protected $table = 'term_relationships';

    /**
     * @var array
     */
    protected $primaryKey = ['object_id', 'term_taxonomy_id'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'object_id');
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class, 'term_taxonomy_id');
    }
}
