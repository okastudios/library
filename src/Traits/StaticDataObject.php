<?php
/*
 *     ____  __
 *    / __ \/ /______ _
 *   / / / / //_/ __ `/
 *  / /_/ / ,< / /_/ /
 *  \____/_/|_|\__,_/
 *
 *  @author Ole K. Aanensen <ole@okastudios.com>
 *  @copyright Copyright (c) 2015, okastudios.com
 *
 */

// Package
namespace Oka\Traits;

/**
 * Class StaticDataObject
 * @package Oka\Traits
 */
trait StaticDataObject
{

    /**
     * @param string $name
     * @return mixed
     */
    public static function Get($name)
    {
        if(!isset(self::$data[$name]))
            return null;

        return self::$data[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public static function Set($name, $value)
    {
        self::$data[$name] = $value;
    }

    /**
     * @return array
     */
    public static function GetData()
    {
        return self::$data;
    }


}