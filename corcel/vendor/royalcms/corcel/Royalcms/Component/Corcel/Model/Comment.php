<?php

namespace Royalcms\Component\Corcel\Model;

use Royalcms\Component\Corcel\Model;
use Royalcms\Component\Corcel\Model\Builder\CommentBuilder;
use Royalcms\Component\Corcel\Traits\HasMetaFields;
use Royalcms\Component\Corcel\Traits\TimestampsTrait;
use Royalcms\Component\Database\Eloquent\Builder;

/**
 * Class Comment
 *
 * @package Royalcms\Component\Corcel\Model
 */
class Comment extends Model
{
    use HasMetaFields;
    use TimestampsTrait;

    const CREATED_AT = 'comment_date';
    const UPDATED_AT = null;

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var string
     */
    protected $primaryKey = 'comment_ID';

    /**
     * @var array
     */
    protected $dates = ['comment_date'];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'comment_post_ID');
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function original()
    {
        return $this->belongsTo(Comment::class, 'comment_parent');
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->original();
    }

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_parent');
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->attributes['comment_approved'] == 1;
    }

    /**
     * @return bool
     */
    public function isReply()
    {
        return $this->attributes['comment_parent'] > 0;
    }

    /**
     * @return bool
     */
    public function hasReplies()
    {
        return $this->replies->count() > 0;
    }

    /**
     * @param \Royalcms\Component\Database\Query\Builder $query
     * @return CommentBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new CommentBuilder($query);
    }

    /**
     * Find a comment by post ID.
     *
     * @param int $postId
     * @return Comment
     */
    public static function findByPostId($postId)
    {
        return (new static())
            ->where('comment_post_ID', $postId)
            ->get();
    }
}
