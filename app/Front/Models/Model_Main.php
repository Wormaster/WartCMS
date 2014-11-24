<?php
namespace Front\Models;
class Model_Main extends \Core\Model {
	function __construct() {
       parent::__construct();
   }
    function get_all($cat = null) {
        if (!is_null($cat)) {
            $this->query = 'SELECT `t1`.*, `t2`.`name` AS `catname` FROM `projects_'.$GLOBALS['lang'].'` AS `t1` LEFT JOIN `categories` AS `t2` ON `t1`.`category` = `t2`.`id` WHERE `t1`.`category` = ? AND `status` = 1 LIMIT 9';
            $result = self::get_data(array($cat));
        }
        else {
            $this->query = 'SELECT `id`,`name`,`order`,`alias` FROM `products_ru` WHERE `extra` = 1 AND `status` = 1 LIMIT 6';
            $result = self::get_data();
        }
        return $result;
    }


   
	}
