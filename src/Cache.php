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
 * Class Cache
 * @package Oka
 */
class Cache {

    /**
     * @var Cache\Driver
     */
    private static $engine;

    /**
     * Returns engine
     * @return Cache\Driver
     */
    public static function GetEngine()
    {
        if(is_null(self::$engine))
        {
            $class = '\\Oka\\Cache\\' . \App\Config\Cache::Get('engine');
            if(class_exists($class))
                self::$engine = new $class;
            else
                trigger_error('Unsupported cache engine', E_USER_ERROR);

        }

        return self::$engine;
    }

    /**
     * Returns whether the key exists in cache
     * @param $key
     * @return boolean
     */
    public static function Exists($key)
    {
        return self::GetEngine()->exists($key);
    }

    /**
     * Returns key from cache
     * @param string $key
     * @return mixed
     */
    public static function Get($key)
    {
        return self::GetEngine()->get($key);
    }

    /**
     * Gets key from cache
     * @param string $key
     * @param Callable $callable
     * @param int $ttl
     * @return mixed
     */
    public static function GetSmart($key, $callable = null, $ttl = null)
    {
        $response = null;
        if(self::GetEngine()->exists($key))
            $response = self::$engine->get($key);
        elseif(is_callable($callable))
        {
            $response = call_user_func($callable);
            self::$engine->set($key, $response, $ttl);
        }

        return $response;

    }

    /**
     * Sets key in cache
     * @param $key
     * @param $value
     * @param null $ttl
     */
    public static function Set($key, $value, $ttl = null)
    {
        self::GetEngine()->set($key, $value, $ttl);
    }

    /**
     * Deletes key from cache
     * @param $key
     */
    public static function Delete($key)
    {
        self::GetEngine()->delete($key);
    }

}