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
 * Class View
 * @package Oka\Web
 */
class View {

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    public $language;

    /**
     * @var array
     */
    public $locale = [];

    /**
     * @param $template
     */
    public function __construct($template)
    {
        $this->template = $template;
        $this->language = \App\Config\System::Get('locale');
    }

    /**
     * Return locale object
     */
    private function getLocale($locale)
    {
        $file = OKA_ROOT.'/App/Locale/' . $this->language . '/' . $locale . '.php';
        if(file_exists($file))
            return include($file);
        else
            return null;
    }

    /**
     * @param string $locale
     */
    public function addLocale($locale)
    {
        if($data = $this->getLocale($locale))
           $this->locale = array_merge($this->locale, $data);
        else
            if(!file_exists(OKA_ROOT.'/App/Locale/' . $this->language))
                trigger_error('Unsupported app language', E_USER_WARNING);
            else
                trigger_error('Language file does not exists', E_USER_WARNING);
    }

    /**
     * Include file from view folder
     * @param string $file
     */
    private function includeFile($file)
    {
        // Add Locale if it exists
        if($locale = $this->getLocale($file))
            $this->locale = array_merge($this->locale, $locale);

        // Include file
        include OKA_ROOT.'/App/Http/View/' . $file . '.php';
    }

    /**
     * Filter locale
     * @param string $str
     * @return string
     */
    private function filter($str)
    {
        $data = [];
        foreach($this->locale as $key => $value)
            $data['{$lang->' . $key . '}'] = $value;

        return str_replace(array_keys($data), array_values($data), $str);
    }

    /**
     * Render
     */
    public function render()
    {
        ob_start([$this, 'filter']);
        $this->includeFile($this->template);
    }

}