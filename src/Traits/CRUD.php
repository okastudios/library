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
namespace Oka\Traits;

// Imports
use Oka\Database;

/**
 * Class CRUD
 * @package Oka\Traits
 */
trait CRUD
{

    /**
     * Converts type (by string) to PDO::PARAM_%type%
     * @param string $type
     * @return int
     */
    private static function TypeToPdoParam($type)
    {
        $types = array(
            'boolean'   => \PDO::PARAM_BOOL,
            'integer'   => \PDO::PARAM_INT,
            'string'    => \PDO::PARAM_STR,
            'NULL'      => \PDO::PARAM_NULL
        );

        return isset($types[$type])?$types[$type]:\PDO::PARAM_STR;
    }

    /**
     * @param int $primary
     * @return bool
     */
    public static function Exists($primary)
    {
        return
            (bool)
            Database::Result(
                "SELECT COUNT(*) FROM `" . self::$table . "` WHERE `" . self::$primary . "` = :primary LIMIT 1",
                [
                    ':primary' => $primary
                ]
            );
    }

    /**
     * @param array $structure
     * @return int
     */
    public static function Create($structure)
    {
        // Filter bad fields
        foreach($structure as $key => $value)
        {
            if(!isset(self::$structure[$key]))
                unset($structure[$key]);
        }

        // Build query
        // Build statement
        $stmt = Database::Prepare("INSERT INTO `" . self::$table . "` (" . implode(',', array_keys($structure)) . ") VALUES (" . implode(',', array_fill(0, count($structure), '?')) . ")");

        // Bind values
        $i = 1;
        foreach($structure as $key => $value)
        {
            $stmt->bindValue($i, $value, self::TypeToPdoParam(self::$structure[$key]));
            $i++;
        }

        // Execute
        $stmt->execute();

        // Return ID
        return Database::GetConnection()->lastInsertId(self::$primary);
    }

    /**
     * @param int $primary
     * @param array $structure
     * @return string|array
     */
    public static function Read($primary, $structure = null)
    {
        // Handle fields
        $fields = '*';
        if(!is_null($structure))
        {
            // Filter bad fields
            foreach($structure as $key => $value)
            {
                if(!isset(self::$structure[$value]))
                    unset($structure[$value]);
            }

            $fields = implode(',', array_map(function($a){return '`' . $a . '`';}, $structure));

        }

        return
            Database::Result(
                "SELECT " . $fields . " FROM `" . self::$table . "` WHERE `" . self::$primary . "` = :primary LIMIT 1",
                [
                    ':primary' => $primary
                ]
            );
    }

    /**
     * @param int $primary
     * @param array $structure
     * @return bool
     */
    public static function Update($primary, $structure)
    {
        // Filter bad fields
        foreach($structure as $key => $value)
        {
            if(!isset(self::$structure[$key]))
                unset($structure[$key]);
        }

        // Build query
        // Build statement
        $stmt = Database::Prepare("UPDATE `" . self::$table . "` SET " . implode(', ', array_map(function($a){return '`' . $a . '` = ?';}, array_keys($structure))) . " WHERE `" . self::$primary . "` = ? LIMIT 1");

        // Bind values
        $i = 1;
        foreach($structure as $key => $value)
        {
            $stmt->bindValue($i, $value, self::TypeToPdoParam(self::$structure[$key]));
            $i++;
        }

        // Bind primary
        $stmt->bindValue($i, $primary, \PDO::PARAM_INT);

        // Execute
        $stmt->execute();

        // Return result
        return (bool) ($stmt->rowCount());
    }

    /**
     * @param int $primary
     * @return bool
     */
    public static function Delete($primary)
    {
        return
            (bool) Database::Result(
                "DELETE FROM `" . self::$table . "` WHERE `" . self::$primary . "` = :primary LIMIT 1",
                [
                    ':primary' => $primary
                ]
            );
    }

}