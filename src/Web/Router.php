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
 * Class Router
 * @package Oka\Web
 */
class Router {

    /**
     * @var array
     */
    private static $data = [];

    /**
     * @param string $pattern
     * @param Callable $callback
     */
    public static function Get($pattern, $callback)
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        self::$data[$pattern] = $callback;
    }

    /**
     * @param string $pattern
     * @param Callable $callback
     */
    public static function Post($pattern, $callback)
    {
        if(!empty($_POST))
            self::Get($pattern, $callback);
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function Find($url)
    {
        $response = null;
        foreach(self::$data as $pattern => $callback)
            if(preg_match($pattern, $url, $params))
            {
                array_shift($params);
                $response = [$callback, $params];
                break;
            }

        return $response;

    }

}