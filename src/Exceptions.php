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

// Imports
use Oka\Misc\Debug;

/**
 * Class Exceptions
 * @package Oka
 */
class Exceptions {

    /**
     * An array with all the errors that have occurred during execution
     * @var array
     */
    private static $errors = [];

    /**
     * @var array
     */
    private static $displayData = [];

    /**
     * Register error into array
     * @param int    $type
     * @param string $message
     * @param string $file
     * @param int    $line
     * @param array  $trace
     * @return array
     */
    private static function RegisterError($type, $message, $file, $line, $trace = null)
    {
        // Don't register error duplicate
        $lastError = end(self::$errors);
        if(!is_null($lastError) && ($lastError['type'] == $type && $lastError['file'] == $file && $lastError['line'] == $line))
            return;

        // Add to error array
        self::$errors[] = [
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'backtrace' => $trace
        ];
    }

    /**
     * Handles php error messages and generates exceptions
     * @param int    $type
     * @param string $message
     * @param string $file
     * @param int    $line
     * @throws \ErrorException
     */
    public static function ErrorHandler($type, $message, $file, $line)
    {
        // Register error
        self::RegisterError($type, $message, $file, $line, debug_backtrace());

        // Display error if it's not suppressed
        if(error_reporting())
        {
            $exception = 'Oka\\Exceptions\\' . Debug::ErrorTypeToExceptionName($type);
            throw new $exception($message, $type, 1, $file, $line);
        }
    }

    /**
     * Checks for fatal errors on shutdown
     */
    public static function ShutdownHandler()
    {
        if($error = error_get_last())
        {
            $exception = 'Oka\\Exceptions\\' . Debug::ErrorTypeToExceptionName($error['type']);
            self::ExceptionHandler(new $exception($error['message'], $error['type'], 1, $error['file'], $error['line']));
        }
    }

    /**
     * Handles uncaught exceptions that occur in the script
     * @param \Exception $error
     */
    public static function ExceptionHandler(\Exception $error)
    {
        /**
         * Register error
         * Does not register error if it is the same as last
         */
        $last = end(self::$errors);
        if(!is_null($last) && ($last['file'] != $error->getFile() && $last['line'] != $error->getLine()))
            self::RegisterError($error->getCode(), $error->getMessage(), $error->getFile(), $error->getLine(), $error->getTrace());

        /**
         * Generate error report
         */
        $report = [
            'exception' => get_class($error),
            'type' => $error->getCode(),
            'message' => $error->getMessage(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTrace(),
            //'history' => self::$errors
        ];

        /**
         * Write log file
         */
        try {
            $log = new Storage\File('Logs/' . Misc\Debug::ErrorTypeToString($error->getCode()) . '-' . time() . '.php.ser');
            $log->write(serialize($report));
            $log->save();
        } catch(\Exception $e) {}

        /**
         * Display error
         */
        self::$displayData = [$error, $report];
        Web::Abort(500);

    }

    /**
     * Display last error
     */
    public static function DisplayError()
    {
        if(\App\Application::$debug)
        {
            $name = 'Default/500/Debug';
            $report = Misc\Debug::DumpSilent(self::$displayData);
        } else
        {
            $name = 'Default/500/Error';
            $key = Cipher\Password::Hash(microtime(true) . rand(1, 99999));
            $report = $key . \Oka\Cipher::Encrypt(serialize(self::$displayData[1]), $key, true);
        }

        $page = new Web\View($name);
        $page->exception = self::$displayData[0];
        $page->report = htmlentities($report);
        $page->render();
    }

}