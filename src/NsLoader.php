<?php

namespace UblTr;

use UblTr\Exception\NsException;
use UblTr\Ns\CommonAggregateComponents2;
use UblTr\Ns\CommonBasicComponents2;
use UblTr\Ns\CommonExtensionComponents2;
use UblTr\Ns\Invoice2;
use UblTr\Ns\Xades;
use UblTr\Ns\XMLSchemaInstance;
use UblTr\Ns\XMLSignatures;

class NsLoader
{
    /**
     * @var string[]
     */
    private static $map = array(
        'cbc' => CommonBasicComponents2::class,
        'cac' => CommonAggregateComponents2::class,
        'xades' => Xades::class,
        'xsi' => XMLSchemaInstance::class,
        'ext' => CommonExtensionComponents2::class,
        'ds' => XMLSignatures::class,
        'xmlns' => Invoice2::class
    );

    /**
     * Loaded ns
     * @var array
     */
    private static $loaded = array();

    /**
     * Singleton ns loader
     *
     * @param $prefix
     * @return mixed
     * @throws NsException
     */
    public static function load($prefix)
    {
        if (!isset(static::$map[$prefix])) throw new NsException("$prefix not found!");
        if (!isset(static::$loaded[$prefix])) {
            static::$loaded[$prefix] = self::create($prefix);
        }
        return static::$loaded[$prefix];
    }

    /**
     * @return array
     */
    public static function loaded()
    {
        return array_values(static::$loaded);
    }

    private static function create($prefix)
    {
        if (class_exists($prefix)) {
            $className = $prefix;
            $prefix = array_flip(self::$map)[$prefix];
        } else {
            $className = static::$map[$prefix];
        }
        $ns = new $className($prefix);
        if (!is_null($parentPrefix = $ns->getParent())) {
            $ns->setParent(static::create($parentPrefix));
        }
        return $ns;
    }

}