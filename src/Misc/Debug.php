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
namespace Oka\Misc;

/**
 * Class Debug
 * @package Oka\Misc
 */
class Debug
{

    /**
     * Returns a trace callback to this function
     * @return string
     */
    public static function Trace()
    {
        $obj = debug_backtrace();
        unset($obj[0]);

        return self::DumpSilent($obj);

    }

    /**
     * Converts variable that is passed to readable format
     * and prints it out
     * @param mixed $obj
     */
    public static function Dump($obj)
    {
        echo self::DumpSilent($obj);
    }


    /**
     * Converts variable that is passed to readable format
     * @param mixed $obj
     * @return string
     */
    public static function DumpSilent($obj)
    {
        ob_start();
        if(is_null($obj) || is_bool($obj) || is_string($obj))
            var_dump($obj);
        else
            print_r($obj);

        return trim(ob_get_clean());
    }

    /**
     * @param int $type
     * @return string
     */
    public static function ErrorTypeToString($type)
    {
        $types = [
            E_ERROR                 => 'E_ERROR',
            E_WARNING               => 'E_WARNING',
            E_PARSE                 => 'E_PARSE',
            E_NOTICE                => 'E_NOTICE',
            E_CORE_ERROR            => 'E_CORE_ERROR',
            E_CORE_WARNING          => 'E_CORE_WARNING',
            E_COMPILE_ERROR         => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING       => 'E_COMPILE_WARNING',
            E_USER_ERROR            => 'E_USER_ERROR',
            E_USER_WARNING          => 'E_USER_WARNING',
            E_USER_NOTICE           => 'E_USER_NOTICE',
            E_STRICT                => 'E_STRICT',
            E_RECOVERABLE_ERROR     => 'E_RECOVERABLE_ERROR',
            E_DEPRECATED            => 'E_DEPRECATED',
            E_USER_DEPRECATED       => 'E_USER_DEPRECATED',
            E_ALL                   => 'E_ALL'
        ];

        return (isset($types[$type]))?$types[$type]:'E_UNKNOWN';
    }

    public static function ErrorTypeToExceptionName($type)
    {
        $exceptionTypes = [
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parser',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'CoreError',
            E_CORE_WARNING => 'WarningError',
            E_COMPILE_ERROR => 'CompileError',
            E_COMPILE_WARNING => 'CompileWarning',
            E_USER_ERROR => 'UserError',
            E_USER_WARNING => 'UserWarning',
            E_USER_NOTICE => 'UserNotice',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'RecoverableError',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'UserDeprecated'
        ];

        return (isset($exceptionTypes[$type]))?$exceptionTypes[$type]:'Unknown';
    }

}