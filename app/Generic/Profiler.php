<?php
/**
 * Created by PhpStorm.
 * User: Arkady
 * Date: 12.09.14
 * Time: 19:34
 *
 * Класс для замеров времени работы нашего велосипеда.
 *
 */

namespace Generic;


class Profiler {
    private $starttime;
    private $endtime;

    private static $instance;

    private function __construct() {

    }

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function start_count(){
        $mtime = microtime();        //Считываем текущее время

        $mtime = explode(" ",$mtime);    //Разделяем секунды и миллисекунды

        // Составляем одно число из секунд и миллисекунд

        // и записываем стартовое время в переменную

        $this->starttime = $mtime[1] + $mtime[0];
    }
    public function end_count(){
        $mtime = microtime();

        $mtime = explode(" ",$mtime);

        $this->endtime = $mtime[1] + $mtime[0];

    }
    public function get_result(){

        $totaltime = ($this->starttime - $this->endtime);//Вычисляем разницу

        return $totaltime;
    }
    public function display_result(){
        $totaltime = ($this->starttime - $this->endtime);//Вычисляем разницу

        echo $totaltime;
    }
} 