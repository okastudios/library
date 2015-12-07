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
namespace Oka;

/**
 * Class Storage
 * @package Oka
 */
class Storage
{
    /**
     * @var string
     */
    private static $path = OKA_ROOT . DIRECTORY_SEPARATOR . 'Data';

    /**
     * Final directory separator
     * @var string
     */
    public static $separator = DIRECTORY_SEPARATOR;

    /**
     * @return string
     */
    public static function GetPath()
    {
        return self::$path;
    }

    /**
     * @param string $path
     */
    public static function SetPath($path)
    {
        self::$path = $path;
    }

    /**
     * @return string
     */
    public static function GetSeparator()
    {
        return self::$separator;
    }

    /**
     * @param string $separator
     */
    public static function SetSeparator($separator)
    {
        self::$separator = $separator;
    }

}