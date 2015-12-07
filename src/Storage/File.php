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
namespace Oka\Storage;

// Imports
use Oka\Storage;

/**
 * Class File
 * @package Oka\Storage
 */
class File {

    /**
     * Default file handler mode
     * http://php.net/manual/en/function.fopen.php
     */
    const DEFAULT_FILE_HANDLE_MODE = 'w';

    /**
     * File's filename
     * @var string
     */
    private $dirname;

    /**
     * File's directory
     * @var string
     */
    private $basename;

    /**
     * File contents
     * @var string
     */
    private $contents;

    /**
     * Boolean if the file has temp edits
     * @var bool
     */
    private $edit = false;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $pathinfo = pathinfo($filename);
        $this->dirname  = $pathinfo['dirname'];
        $this->basename = $pathinfo['basename'];
    }

    /**
     * Returns the full path to file
     * @return string
     */
    public function getPath()
    {
        return str_replace(
            ['/', '\\'],
            Storage::GetSeparator(),
            Storage::GetPath() . '/' . $this->dirname . '/' . $this->basename
        );
    }

    /**
     * Checks if the file exists on the disk
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->getPath());
    }

    /**
     * Reads file and returns content
     * @return string
     */
    public function get()
    {
        if(is_null($this->contents) && $this->exists())
            $this->contents = file_get_contents($this->getPath());

        return $this->contents;
    }

    /**
     * Reads file and prints content
     *
     * readfile() will not present any memory issues, even when sending large files, on its own.
     * If you encounter an out of memory error ensure that output buffering is off
     * with ob_get_level().
     */
    public function read()
    {
        if(!$this->edit && $this->exists())
            readfile($this->getPath());
        else
            echo $this->contents;
    }

    /**
     * (Over)writes content
     * @param string $content
     */
    public function write($content)
    {
        $this->contents = $content;
        $this->edit = true;
    }

    /**
     * Appends to content
     * @param $content
     */
    public function append($content)
    {
        $this->contents = $this->get() . $content;
        $this->edit = true;
    }

    /**
     * Displays file in browser
     * @param string $header
     */
    public function view($header = null)
    {
        if(is_null($header))
        {
            $extensions = array(
                'txt'   =>  'text/plain',
                'xml'   =>  'text/xml',
                'json'  =>  'application/json',
                'js'    =>  'text/javascript',
                'css'   =>  'text/css',
                'pdf'   =>  'application/pdf',
                'jpg'   =>  'image/jpeg',
                'jpeg'  =>  'image/jpeg',
                'jpe'   =>  'image/jpeg',
                'png'   =>  'image/png',
                'gif'   =>  'image/gif'
            );

            $header = isset($extensions[$this->getExtension()])?$extensions[$this->getExtension()]:null;
        }

        if(!is_null($header))
            header('Content-Type: ' . $header);

        \Oka\Web\Buffer::Clean();
        $this->read();
    }

    /**
     * Downloads file to visitor
     */
    public function download()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $this->getFilename());
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $this->getSize());
        \Oka\Web\Buffer::Clean();

        $this->read();
        exit;
    }

    /**
     * Saves class state to file
     * @param string $handle
     * @return bool
     * @throws \Oka\Exceptions\IO
     */
    public function save($handle = self::DEFAULT_FILE_HANDLE_MODE)
    {
        // File was not edited so skip save
        if(!$this->edit && $this->exists())
            return true;

        // Full file path
        $path = $this->getPath();

        // Make directory if it does not exists
        if(!file_exists(dirname($path)))
            mkdir(dirname($path), 00777, true);

        // Write file
        if($fp = fopen($path, $handle))
        {
            if($result = fwrite($fp, $this->contents) !== false)
            {
                // Contents have been saved
                $this->edit = false;

                // Close IO stream
                fclose($fp);
                return ($result);

            } else
            {
                fclose($fp);
                trigger_error('Could not write to file', E_USER_WARNING);
            }
        }

        return false;
    }

    /**
     * Deletes file and destroys class object
     */
    public function delete()
    {
        if($this->exists())
            unlink($this->getPath());
    }

}