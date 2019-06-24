<?php

namespace Royalcms\Component\Corcel\Model\Meta;

use Royalcms\Component\Corcel\Model\Comment;

/**
 * Class CommentMeta
 *
 * @package Royalcms\Component\Corcel\Model\Meta
 */
class CommentMeta extends PostMeta
{
    /**
     * @var string
     */
    protected $table = 'commentmeta';

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
