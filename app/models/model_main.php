<?php
class Model_Main extends Model {
	function __construct() {
       parent::__construct();
   }
    function cloud() {
        $this->query = 'SELECT * FROM `ds_cloud`';
        $items = $this->get_data();
        return $items;
    }

   
	}
