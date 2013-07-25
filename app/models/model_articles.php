<?php
class Model_Articles extends Model {
	function __construct() {
       parent::__construct();
	   $this->query = 'SELECT * FROM `wart_calc_categories`';
   }
   
   function get_item($id, $cat, $stat) {
	   	$this->query = 'SELECT * FROM `articles_'.$GLOBALS['lang'].'` WHERE `id` = ? AND `category` = ? AND `status` = ? LIMIT 1';
		$items = self::get_data(array($id, $cat, $stat));
		return $items;  
	   }
	   
	function list_items() {
	   	$this->query = 'SELECT `id`, `menu`, `alias`, `order` FROM `articles_'.$GLOBALS['lang'].'` WHERE `id` > 1 AND `status` = 1 ORDER BY `order` ASC, `id` ASC';
		$result = self::get_data();
		return $result;  
	   }
	}
