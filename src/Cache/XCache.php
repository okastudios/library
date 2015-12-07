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
namespace Oka\Cache;

/**
 * Class XCache
 * @package Oka\Cache
 */
class XCache implements Driver
{

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return xcache_isset($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return xcache_get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        return xcache_set($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

}