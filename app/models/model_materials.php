<?php
class Model_Materials extends Model {
	function __construct() {
       parent::__construct();
	   $this->query = 'SELECT * FROM `wart_calc_categories`';
   }
   
    function get_all() {
        if (!empty($_REQUEST['category'])){
            $data = array ($_REQUEST['category']);
        }
        else {
            $data = array ('1');
        }
        $this->query = 'SELECT * FROM `materials_'.$GLOBALS['lang'].'` WHERE `status` = 1 AND `category` = ? ORDER BY `order` ASC, `id` ASC';
        $result = self::get_data($data);
        return $result;
    }
    function get_cat() {
        if (!empty($_REQUEST['category'])){
            $data = array ($_REQUEST['category']);
        }
        else {
            $data = array ('1');
        }
        $this->query = 'SELECT * FROM `categories` WHERE `id` = ?';
        $result = self::get_data($data);
        return $result;

    }
    function get_allcat() {
        $this->query = 'SELECT `id`, `name` FROM `categories`';
        $result = self::get_data();
        return $result;

    }
}
