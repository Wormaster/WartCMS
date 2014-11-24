<?php
/**
 * Created by PhpStorm.
 * User: Arkady
 * Date: 27.03.14
 * Time: 22:30
 */

namespace Core;


class Language {
    protected $lang;
    protected $reg;
    protected $langfile;
    protected $words;
    private $template;

    function __construct(){
        $this->reg = \Core\Registry::instance();
        $this->lang = $this->reg['language'] ? $this->reg['language'] : 'ru';

        $this->langfile = site_path . 'lang' . DS . $this->lang . DS . 'default.ini';
        $this->words = parse_ini_file($this->langfile);
        //var_dump($this->words);
    }
    function get($word = null){
        $words = $this->words;
        if ($word) {
            if ($words[$word]){
                return $words[$word];
            }
            else {
                return false;
            }
        }
        else {
            return $words;
        }

    }

} 