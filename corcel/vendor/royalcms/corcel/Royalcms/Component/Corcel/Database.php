<?php

namespace Royalcms\Component\Corcel;

use Royalcms\Component\Database\Capsule\Manager as Capsule;

/**
 * Class Database
 *
 * @package Royalcms\Component\Corcel
 */
class Database
{
    /**
     * @var array
     */
    protected static $baseParams = [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => 'wp_',
    ];

    /**
     * @param array $params
     * @return \Royalcms\Component\Database\Capsule\Manager
     */
    public static function connect(array $params)
    {
        $capsule = new Capsule();

        $params = array_merge(static::$baseParams, $params);
        $capsule->addConnection($params);
        $capsule->bootEloquent();

        return $capsule;
    }
}
