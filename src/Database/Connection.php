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
namespace Oka\Database;

/**
 * Class Connection
 * @package Oka\Database
 */
class Connection extends \PDO
{

    public function result($query, $parameters = null, $translateRows = true)
    {

        // Run query
        $stmt = $this->prepare($query);
        $result = $stmt->execute($parameters);

        // Process result
        switch(explode(' ', $query)[0])
        {
            case "SELECT":
                $response = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                if(empty($response))
                    $response = null;
                elseif($translateRows && count($response) == 1) {
                    $response = end($response);
                    if(count($response) == 1)
                        $response = end($response);
                }

                return $response;

            case "INSERT":
                return $this->lastInsertId();

            case "UPDATE":
            case "DELETE":
                return $stmt->rowCount();

            default:
                return $result;
        }

    }

}