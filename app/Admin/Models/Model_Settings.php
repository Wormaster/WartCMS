<?php
namespace Admin\Models;
class Model_Settings extends \Core\Model {
    function __construct() {
        parent::__construct();
    }


    function save_color(){
        $its = preg_match('/^#[0-9a-fA-F]{6}/',$_REQUEST['color']);

        if (preg_match('/^#[0-9a-fA-F]{6}/',$_REQUEST['color'])){
            $this->query = 'UPDATE `settings` SET `value` = ? WHERE `type` = \'color\'';
            $result = $this->set_data(array($_REQUEST['color']));
            return $result;
        }
        else return false;
    }
    function get_ctypes(){
        $this->query = 'SELECT * FROM `content_types`';
        $ctypes = $this->get_data();
        if ($ctypes){
            return $ctypes;
        }
        else return false;
    }
    function get_cfields(){
        $this->query = 'SELECT * FROM `field_types`';
        $cfields = $this->get_data();
        if ($cfields){
            return $cfields;
        }
        else return false;
    }
}
	