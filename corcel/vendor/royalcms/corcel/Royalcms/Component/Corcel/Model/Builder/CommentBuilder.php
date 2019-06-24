<?php

namespace Royalcms\Component\Corcel\Model\Builder;

use Royalcms\Component\Database\Eloquent\Builder;

/**
 * Class CommentBuilder
 *
 * @package Royalcms\Component\Corcel\Model\Builder
 */
class CommentBuilder extends Builder
{
    /**
     * @return CommentBuilder
     */
    public function approved()
    {
        return $this->where('comment_approved', 1);
    }
}
