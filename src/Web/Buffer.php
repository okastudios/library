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
namespace Oka\Web;

/**
 * Class Buffer
 * @package Oka\Web
 */
class Buffer
{

    public static function Start($callback = null)
    {
        return ob_start($callback);
    }

    public static function Clean()
    {
        ob_clean();
    }

    public static function Fetch()
    {
        return ob_get_contents();
    }

    public static function Output()
    {
        return ob_end_flush();
    }

    public static function Discard()
    {
        return ob_end_clean();
    }

    public static function DiscardAll()
    {
        while(@ob_end_clean());
    }

}