<?php

namespace Royalcms\Component\Corcel\Model\Meta;

use Royalcms\Component\Corcel\Model\Term;

/**
 * Class TermMeta
 *
 * @package Royalcms\Component\Corcel\Model\Meta
 */
class TermMeta extends PostMeta
{
    /**
     * @var string
     */
    protected $table = 'termmeta';

    /**
     * @var array
     */
    protected $fillable = ['meta_key', 'meta_value', 'term_id'];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
