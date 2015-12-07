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
 * Class APC
 * @package Oka\Cache
 */
class APC implements Driver
{

    /**
     * @param string $key
     * @return bool
     */
    public function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     * @return bool
     */
    public function set($key, $value, $ttl = null)
    {
        return apc_store($key, $value, $ttl);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        return apc_delete($key);
    }

}