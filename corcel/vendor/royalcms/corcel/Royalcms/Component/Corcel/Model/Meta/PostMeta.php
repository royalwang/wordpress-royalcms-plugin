<?php

namespace Royalcms\Component\Corcel\Model\Meta;

use Royalcms\Component\Corcel\Model;
use Royalcms\Component\Corcel\Model\Collection\MetaCollection;
use Royalcms\Component\Corcel\Model\Post;
use Royalcms\Component\Corcel\Model\Taxonomy;
use Exception;

/**
 * Class PostMeta
 *
 * @package Royalcms\Component\Corcel\Model\Meta
 */
class PostMeta extends Model
{
    /**
     * @var string
     */
    protected $table = 'postmeta';

    /**
     * @var string
     */
    protected $primaryKey = 'meta_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['meta_key', 'meta_value', 'post_id'];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @var array
     */
    protected $appends = ['value'];

    /**
     * @return mixed
     */
    public function getValueAttribute()
    {
        try {
            $value = unserialize($this->meta_value);

            return $value === false && $this->meta_value !== false ?
                $this->meta_value :
                $value;
        } catch (Exception $ex) {
            return $this->meta_value;
        }
    }

    /**
     * @param string $primary
     * @param string $where
     * @return \Royalcms\Component\Database\Eloquent\Relations\Relation
     * @todo test
     */
    public function taxonomy($primary = null, $where = null)
    {
        // possible to exclude a relationship connection
        if (!is_null($primary) && !empty($primary)) {
            $this->primaryKey = $primary;
        }

        $relation = $this->hasOne(Taxonomy::class, 'term_taxonomy_id');

        if (!is_null($where) && !empty($where)) {
            $relation->where($where, $this->meta_value);
        }

        return $relation;
    }

    /**
     * @param array $models
     * @return MetaCollection
     */
    public function newCollection(array $models = [])
    {
        return new MetaCollection($models);
    }
}
