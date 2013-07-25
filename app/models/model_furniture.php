<?php
class Model_Furniture extends Model {
	function __construct() {
       parent::__construct();
	   $this->query = 'SELECT * FROM `projects`';
   }
	function get_item() {
        if (!empty($_REQUEST['id'])) {
	   	$this->query = 'SELECT * FROM `projects_'.$GLOBALS['lang'].'` WHERE `id` = ? AND `status` = 1 LIMIT 1';
		$result = self::get_data(array($_REQUEST['id']));
		return $result; 
		} else {
			return 'Что-то пошло не так...';}

	   }
	function get_all() {
        if (!empty($_REQUEST['category'])){
            $data = array ($_REQUEST['category']);
            }
        else {
            $data = array ('1');
            }
        $this->query = 'SELECT `id`, `menu`, `alias` FROM `projects_'.$GLOBALS['lang'].'` WHERE `status` = 1 AND `category` = ? ORDER BY `order` ASC, `id` ASC';
		$result = self::get_data($data);
		return $result; 
	   }
	
	}