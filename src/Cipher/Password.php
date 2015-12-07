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
namespace Oka\Cipher;

/**
 * Class Password
 * @package Oka\Cipher
 */
class Password
{

    /**
     * Generate hash for password
     * @param string $password
     * @return bool|string
     */
    public static function Hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify if hash matches password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function Verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

}