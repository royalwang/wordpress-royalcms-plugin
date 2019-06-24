<?php

namespace Royalcms\Component\Corcel\Model;

use Royalcms\Component\Corcel\Model;
use Royalcms\Component\Corcel\Traits\HasAcfFields;
use Royalcms\Component\Corcel\Traits\HasMetaFields;

/**
 * Class Term.
 *
 * @package Royalcms\Component\Corcel\Model
 */
class Term extends Model
{
    use HasMetaFields;
    use HasAcfFields;

    /**
     * @var string
     */
    protected $table = 'terms';

    /**
     * @var string
     */
    protected $primaryKey = 'term_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\HasOne
     */
    public function taxonomy()
    {
        return $this->hasOne(Taxonomy::class, 'term_id');
    }
}
