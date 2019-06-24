<?php

namespace Royalcms\Component\Corcel\Model\Meta;

/**
 * Class UserMeta
 *
 * @package Royalcms\Component\Corcel\Model\Meta
 */
class UserMeta extends PostMeta
{
    /**
     * @var string
     */
    protected $table = 'usermeta';

    /**
     * @var string
     */
    protected $primaryKey = 'umeta_id';

    /**
     * @var array
     */
    protected $fillable = ['meta_key', 'meta_value', 'user_id'];

    /**
     * @return \Royalcms\Component\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Royalcms\Component\Corcel\User');
    }
}
