<?php
namespace Admin\Models;
class Model_Feedback extends \Core\Model {
    function __construct() {
        parent::__construct();
    }


    function list_feedbacks($type) {
        $this->query = 'SELECT * FROM `feedback` WHERE `type` = ?';
        $result = $this->get_data(array($type));
        if ($result){
            $unicon = new Model_Unimanager();
            foreach ($result as &$one){
                $one['value'] = unserialize($one['value']);
                if ($one['itemid']){
                    $ctype = array('alias' => 'products');
                    $product = $unicon->get_materials_by_type($ctype, (int)$one['itemid']);
                    if ($product){
                        $cat = $this->get_cat_by_id($product['category']);
                        $product['category'] = $cat['alias'];
                        $one['product'] = $product;
                    }
                }
            }
            return $result;
        }
        else {
            return false;
        }

    }

    function clearcalls() {
        $this->query = 'DELETE FROM `feedback` WHERE `type` = \'call\'';
        $result = $this->get_data();
        if ($result){
            return $result;
        }
        else {
            return false;
        }

    }
    function clearfiles() {
        $this->query = 'DELETE FROM `feedback` WHERE `type` = \'file\'';
        $result = $this->get_data();
        self::RemoveDir(site_path.DS.'upfiles');
        if ($result){
            return $result;
        }
        else {
            return false;
        }

    }
    function clearmessages() {
        $this->query = 'DELETE FROM `feedback` WHERE `type` = \'message\'';
        $result = $this->get_data();
        if ($result){
            return $result;
        }
        else {
            return false;
        }

    }
}
	