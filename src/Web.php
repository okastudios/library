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
 * Class Web
 * @package Oka
 */
class Web {

    /**
     * @var string
     */
    private static $controller = [__CLASS__, 'Abort'];

    /**
     * @var array
     */
    private static $params = [404];

    /**
     * Keys that will get filtered in output
     * @var array
     */
    public static $keys = [];

    /**
     * @var array
     */
    public static $aborts = [
        500 => ['\\Oka\\Exceptions', 'DisplayError']
    ];

    /**
     * Handle Request
     */
    public static function Execute()
    {

        // Define global $_KEY array
        $GLOBALS['_KEY'] = array();

        // Initialize Web Applicaiton
        \App\Http\Application::Initialize();

        // Register routes
        include OKA_ROOT.'/App/Http/Routes.php';

        // Register app routes
        \App\Http\Application::Routes();

        // Find right route
        $route = Web\Router::Find(explode('?', $_SERVER['REQUEST_URI'])[0]);
        if(!is_null($route))
        {
            if($route[0] instanceof \Closure)
                self::$controller = $route[0];
            else
            {
                // Handle (at) operator
                if(is_string($route[0]) && (strpos($route[0], '@') !== false))
                    $route[0] = explode('@', $route[0]);

                // Register controller and method
                self::$controller = array(
                    '\App\Http\Controller\\' . $route[0][0],
                    $route[0][1]
                );
            }

            // Register parameters passed to the controller
            self::$params = $route[1];
        }

        // Register output filter
        Web\Buffer::Start([__CLASS__, 'Filter']);

        // Call controller
        $response = call_user_func_array(self::$controller, self::$params);
        if(!is_null($response))
        {
            if(is_array($response))
            {

                // Clean buffer
                // Web\Buffer::Discard();
                Web\Buffer::Clean();

                // Headers
                header("Content-Type: application/json");

                // Output
                echo json_encode($response);

            } elseif(is_string($response))
                echo $response;
        }

    }

    /**
     * Abort execution with error page.
     * @param int $code
     */
    public static function Abort($code)
    {

        // Clean output
        Web\Buffer::Clean();

        // Set headers
        $headers = array(
            403 => '403 Forbidden',
            404 => '404 Not Found',
            500 => '500 Internal Server Error',
            502 => '502 Bad Gateway',
            503 => '503 Service Unavailable'
        );

        if(isset($headers[$code]))
            header($_SERVER["SERVER_PROTOCOL"] . ' ' . $headers[$code], true, $code);

        // Show page
        if(isset(self::$aborts[$code]))
            call_user_func(self::$aborts[$code]);
        else
        {
            $page = new Web\View('Default/' . $code);
            $page->render();
        }

        // Kill request
        exit;
    }

    public static function Filter($str)
    {
        $keys = self::$keys;
        foreach($GLOBALS['_KEY'] as $key => $value)
        {
            $keys['{' . $key . '}'] = $value;
        }

        return str_replace(array_keys($keys), array_values($keys), $str);
    }

}