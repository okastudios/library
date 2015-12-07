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
namespace Oka\Session;

/**
 * Class Manager
 * @package Oka\Session
 */
class Manager
{

    /**
     * @var boolean
     */
    private static $started;

    /**
     * Starts session if it's not already done.
     */
    public static function Start()
    {
        if(!self::$started && session_status() == PHP_SESSION_NONE)
            session_start();
    }

    /**
     * Destroys session
     */
    public static function Destroy()
    {
        self::Start();
        session_destroy();
    }

}