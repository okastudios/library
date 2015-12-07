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
 * Class Session
 * @package Oka
 */
class Session {

    /**
     * Name of the session
     * @var string
     */
    private $name;

    /**
     * Construct the class
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Checks if the variable exists
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        Session\Manager::Start();
        return isset($_SESSION[$this->name][$name]);
    }

    /**
     * Return the value of session variable
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        Session\Manager::Start();
        return $_SESSION[$this->name][$name];
    }

    /**
     * Sets value of session variable
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        Session\Manager::Start();
        $_SESSION[$this->name][$name] = $value;
    }

    /**
     * Deletes session variable
     * @param string $name
     */
    public function __unset($name = null)
    {
        Session\Manager::Start();
        unset($_SESSION[$this->name][$name]);
    }

    /**
     * Check if parent session name exists
     * @return bool
     */
    public function exists()
    {
        Session\Manager::Start();
        return isset($_SESSION[$this->name]);
    }

    /**
     * Destroies parent session
     */
    public function destroy()
    {
        Session\Manager::Start();
        unset($_SESSION[$this->name]);
    }

    /**
     * Gets all values in session variable
     * @return mixed
     */
    public function getData()
    {
        Session\Manager::Start();

        $response = [];
        if(isset($_SESSION[$this->name]))
        {
            $response = $_SESSION[$this->name];
        }

        return $response;
    }

    /**
     * Sets session variables from array
     * @param array $data
     */
    public function setData($data)
    {
        if(!empty($data) && is_array($data))
        {
            Session\Manager::Start();
            $_SESSION[$this->name] = array_merge($_SESSION[$this->name], $data);
        } else
            trigger_error('Expecting first argument to be array with value', E_USER_WARNING);
    }

}