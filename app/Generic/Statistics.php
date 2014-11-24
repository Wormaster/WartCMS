<?php
/**
 * Created by PhpStorm.
 * User: Arkady
 * Date: 26.08.14
 * Time: 21:45
 */

namespace Generic;


class Statistics extends \Core\Model{
    private $sitemodel;

    function __construct(){
        parent::__construct();
        session_start();
    }

    public function collect_data(){
        if(!isset($_SESSION['counted'])){

            $rnd = $this->randomgen(6);

            $user = isset($_SESSION['admin']) ? $_SESSION['admin']: 'no';
            $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']: 'no';


            if($ref == ""){
                $ref = "None";
            }
            if($user == ""){
                $user = "None";
            }
            $data = array(
                'agent' => $_SERVER['HTTP_USER_AGENT'],
                'uri' => $_SERVER['REQUEST_URI'],
                'user' => $user,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'ref' => $ref,
                'hits' => 1,
                'rnd' => $rnd,
                'dtime' => date('c')
            );

            $this->query = $this->insert_query_generator($data, 'stat');
            $result = $this->set_data($data);
            $_SESSION['counted'] = $rnd;
        }
        else {
            $rnd = $_SESSION['counted'];
            $this->query = 'UPDATE `stat` SET `hits` = `hits` + 1 WHERE `rnd` = ?';
            $result = $this->set_data(array($rnd));
        }
    }
    public function get_stat_by_day(){

        $this->query = 'SELECT * FROM `stat` WHERE `dtime` >= CURDATE()';
        $data = $this->get_data();
        $this->query = 'SELECT sum(`hits`) as `total_hits`  FROM `stat` WHERE `dtime` >= CURDATE()';
        $hits = $this->get_data();

        if ($data){
            $result['stat'] = $data;
            $result['hosts'] = count($data);
            $result['hits'] = $hits[0]['total_hits'];

            return $result;
        }
        else {
            $result['stat'] = false;
            $result['hosts'] = 0;
            $result['hits'] = 0;

            return $result;
        }
    }
    public function get_stat_dy_month($month){

    }
} 