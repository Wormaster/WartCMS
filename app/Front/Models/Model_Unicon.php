<?php
namespace Front\Models;
class Model_Unicon extends \Core\Model {

    private $fields;

	function __construct() {
       parent::__construct();
   }

    public function get_materials_by_type($ctypealias, $id = null, $short = false){
        // @todo Проверочку бы алиса... он конечно ниоткуда приходить не должен но всетаки.. да там запрос чере prepared

        $ctype = $this->get_content_type($ctypealias, 'alias');
        if ($ctype) {
            $query = 'SELECT * FROM `'.$ctype['alias'].'` AND `status` = 1';
            if ($id){
                $query .= ' WHERE id = ?';
                $this->query = $query;
                $result = $this->get_data(array($id));
            }
            else {
                $this->query = $query;
                $result = $this->get_data();
            }
            if ($result){
                $result = $this->prepare_materials($ctype, $result, $short);
                if (count($result > 1)){
                    return $result;
                }
                else {
                    return $result[0];
                }
            }
            else return false;
        }
        else {
            return false;
        }
    }
    public function get_materials_by_field($ctype, $field, $value, $cat = null){
        if (is_array($ctype)){
            //ниче не делаем все уже есть
        }
        else {
            $ctype = $this->get_content_type($ctype, 'alias');
        }
        if (is_array($value)){
            $in1  = str_repeat('?,', count($value) - 1) . '?';
            $prequery = 'IN ('.$in1.')';
            $request = $value;
        }
        else {
            $prequery = '= ?';
            $request = array($value);
        }
        if ($ctype){
            $this->query = '
                          SELECT `t1` . * , `t2`.`alias` AS `cat_alias`
                          FROM `'.$ctype['alias'].'` AS `t1`
                          LEFT JOIN `categories` as `t2`
                          ON `t1`.`category` = `t2`.`id`
                          WHERE `t1`.`'.$field.'` '.$prequery.' AND `status` = 1';
            if ($cat){
                $in2  = str_repeat('?,', count($cat) - 1) . '?';
                $this->query .= ' AND `t1`.`category` IN ('.$in2.')';

                $request = array_merge($request, $cat);
            }

            //var_dump($this->query);
            $result = $this->get_data($request);
            if ($result){
                $result = $this->prepare_materials($ctype, $result);
                return $result;
        }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function get_materials_by_alias($catalias, $alias){
        // @todo Слить функции вытягивания по ID и алиасу в 1
        $cat = $this->get_cat_by_alias($catalias);
        $ctype = $this->get_content_type($cat['type'], 'alias');
        if ($ctype) {
            $this->query = 'SELECT * FROM `'.$ctype['alias'].'` WHERE `alias` = ? AND `status` = 1 LIMIT 1';

            $result = $this->get_data(array($alias));
            if ($result){
                $result = $this->prepare_materials($ctype, $result);
                    return $result[0];

            }
            else return false;
        }
        else {
            return array('error' => true, 'message' => 'Ошибка в определении типа контента');
        }
    }
    public function get_materials_by_cat($cat, $by = 'alias'){
        // @todo Проверочку бы категории... он конечно ниоткуда приходить не должен но всетаки..
        if ($by == 'alias'){
            $category = $this->get_cat_by_alias($cat);
            }
        elseif ($by == 'id'){
            $category = $this->get_cat_by_id($cat);
        }

        if ($category){
            $ctype = $this->get_content_type($category['type'], 'alias');

            if ($ctype) {
                $query = 'SELECT * FROM `'.$ctype['alias'].'` WHERE `category` = ? AND `status` = 1';

                $this->query = $query;
                $result = $this->get_data(array($category['id']));
                if ($result){
                    $result = $this->prepare_materials($ctype, $result);

                    return $result;

                }
                else return false;
            }
            else {
                return array('error' => true, 'message' => 'Такого типа контента не существует');
            }
        }
        else {
            return array('error' => true, 'message' => 'Такой категории не существует');
        }
    }

    public function prepare_materials($ctype, $materials, $short = false){
        $fields = $this->get_fields($ctype['id']);
        if ($fields){
            foreach ($materials as &$item){
                foreach ($fields as $field){
                    switch ($field['alias']){
                        case 'image' : {
                            if (is_string($item[$field['field_alias']])){
                                $imgs = unserialize($item[$field['field_alias']]);
                                $image_settings = explode(';', $field['image_settings']);
                                if ($imgs){
                                    foreach ($imgs as &$img){
                                        $img['width'] = $image_settings[0];
                                        $img['height'] = $image_settings[1];
                                    }
                                }
                                    $item[$field['field_alias']] = $imgs;


                                $item['imagefolder'] = $ctype['alias'];
                            }
                            break;
                        }
                        case 'assign' : {
                            if (!$short){
                                $assignctype = $this->get_content_type($field['assign_id']);
                                if($assignctype){
                                    $assignfields = $this->get_fields($assignctype['id']);
                                    if ($field['multiple']){
                                        if (is_string($item[$field['field_alias']])){
                                            $amats = unserialize($item[$field['field_alias']]);
                                        }
                                        else return false;
                                    }
                                    else {
                                        $amats = array($item[$field['field_alias']]);
                                    }
                                    if (is_array($amats)){
                                        $in  = str_repeat('?,', count($amats) - 1) . '?';
                                        $this->query = '
                                                    SELECT `t1` . * , `t2`.`alias` AS `cat_alias`
                                                    FROM `'.$assignctype['alias'].'` AS `t1`
                                                    LEFT JOIN `categories` as `t2`
                                                    ON `t1`.`category` = `t2`.`id`
                                                    WHERE `t1`.`id` IN ('.$in.')';
                                        $result = $this->get_data($amats);
                                        if($result){
                                            foreach ($result as &$res){
                                                foreach($assignfields as $afield){
                                                    switch ($afield['alias']){
                                                        case 'image' : {
                                                            if (is_string($res[$afield['field_alias']])){
                                                                $imgs = unserialize($res[$afield['field_alias']]);
                                                                $image_settings = explode(';', $afield['image_settings']);
                                                                if ($imgs){
                                                                    foreach ($imgs as &$img){
                                                                        $img['width'] = $image_settings[0];
                                                                        $img['height'] = $image_settings[1];
                                                                    }
                                                                }
                                                                $res[$afield['field_alias']] = $imgs;


                                                                $res['imagefolder'] = $assignctype['alias'];
                                                            }
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                            $item[$field['field_alias']] = $result;
                                        }
                                    }




                                }
                                //var_dump($this->query);
                                //var_dump($item[$field['field_alias']]);

                            }
                            else {

                            }
                            break;

                        }
                        case 'select' : {
                            if (is_string($item[$field['field_alias']])){
                                $unserial = unserialize($item[$field['field_alias']]);
                                $item[$field['field_alias']] = json_encode($unserial);
                            }
                            break;
                        }
                    }


                }
            }
            return $materials;
        }
        return $materials;
    }
    public function parametrize_materials($materials, $param, $value){
            foreach ($materials as $mat){
                if(is_array($mat[$param])){
                    foreach ($mat[$param] as $f){
                        if ($f['id'] == $value){
                            $filtered[] = $mat;
                            break;
                        }

                    }
                }
                else {

                }
                //var_dump($mat[$param]);
            }
            if ($filtered){
                return $filtered;
            }
        else {
            return false;
        }
    }

    public function get_cat_by_type($alias){
        $ctype = $this->get_content_type($alias, 'alias');
        if ($ctype){
            $this->query = 'SELECT * FROM `categories` WHERE `type` = ?';
            $result = $this->get_data(array($ctype['alias']));
            if ($result){
                foreach ($result as &$one){
                    $one['image'] = unserialize($one['image']);
                }
                return $result;
            }
            else {
                return array('error' => true, 'message' => 'В данном типе пока нет категорий');
            }
        }
        else {
            return array('error' => true, 'message' => 'Такого типа контента не существует');
        }
    }

    public function get_type_by_cat($alias){
        $ctype = $this->get_content_type($alias, 'alias');
        if ($ctype){
            $this->query = 'SELECT * FROM `categories` WHERE `type` = ?';
            $result = $this->get_data(array($ctype['alias']));
            if ($result){
                return $result;
            }
            else {
                return array('error' => true, 'message' => 'В данном типе пока нет категорий');
            }
        }
        else {
            return array('error' => true, 'message' => 'Такого типа контента не существует');
        }
    }
    function get_neighbours($id, $cat) {
        $ctype = $this->get_content_type($cat['type'], 'alias');
        if ($ctype){
            $this->query = 'SELECT `id`, `name`, `alias` FROM `'.$ctype['alias'].'` WHERE  `id` < ? AND `category` = ? AND `status` = 1 ORDER BY `id` DESC LIMIT 0 , 1';
            $prev = self::get_data(array($id, $cat['id']));
            if (empty($prev)){
                $this->query = 'SELECT `id`, `name`, `alias` FROM `'.$ctype['alias'].'` WHERE  `category` = ? AND `status` = 1 ORDER BY `id` DESC LIMIT 0 , 1';
                $prev = self::get_data(array($cat['id']));
            }
            $result['prev'] = $prev[0];

            $this->query = 'SELECT `id`, `name`, `alias` FROM `'.$ctype['alias'].'` WHERE  `id` > ? AND `category` = ? AND `status` = 1 ORDER BY `id` ASC LIMIT 0 , 1';
            $next = self::get_data(array($id, $cat['id']));
            if (empty($next)){
                $this->query = 'SELECT `id`, `name`, `alias` FROM `'.$ctype['alias'].'` WHERE  `category` = ? AND `status` = 1 ORDER BY `id` ASC LIMIT 0 , 1';
                $next = self::get_data(array($cat['id']));
            }
            $result['next'] = $next[0];
            return $result;
        }
        else {
            return array('error' => true, 'message' => 'Такого типа контента не существует');
        }
    }
    function get_models_by_brand($type = 'brands'){
        $brands = $this->get_materials_by_type($type);
        //$this->firephp->log($brands, 'brands');
        $models = $this->get_materials_by_type('models', null, true);
        if ($brands){
            foreach ($brands as &$brand){
                foreach ($models as $model){
                    if ($brand['id'] == $model['brand']){
                        $brand['items'][] = $model;
                    }
                }
            }
        }
        return $brands;
    }


}