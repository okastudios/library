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
 * Class Database
 * @package Oka
 */
class Database {

    /**
     * @var Database\Connection
     */
    private static $connection;

    /**
     * @return Database\Connection
     */
    public static function GetConnection()
    {
        if(is_null(self::$connection))
        {

            // Connect to database
            $credentials = \App\Config\Database::GetAll();
            self::$connection = new Database\Connection(
                $credentials['dsn'],
                $credentials['username'],
                $credentials['password'],
                isset($credentials['options'])?$credentials['options']:null
            );

            // Set exceptions error mode
            self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        }

        return self::$connection;
    }

    /**
     * @param string $query
     * @return \PDOStatement
     */
    public static function Query($query)
    {
        return self::GetConnection()->query($query);
    }

    /**
     * @param string $query
     * @return \PDOStatement
     */
    public static function Prepare($query)
    {
        return self::GetConnection()->prepare($query);
    }

    /**
     * @param string $query
     * @param array $parameters
     * @param boolean $translateRows
     * @return bool|string|array
     */
    public static function Result($query, $parameters = null, $translateRows = true)
    {
        return self::GetConnection()->result($query, $parameters, $translateRows);
    }

}