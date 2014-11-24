<?php
namespace Admin\Models;
class Model_Unimanager extends \Core\Model {

    function __construct() {
        parent::__construct();

    }



    /* Universal Content System Management - Unimanager */


    public function new_material(){
        if ((empty($_REQUEST['name']) || ($_REQUEST['name'] == ''))
            &&
            (empty($_REQUEST['ctype']) || ($_REQUEST['ctype'] == '')))
        {
            return array('error'=> true, 'message' => 'Ошибка в имени материала или ID Типа');
        }
        else {
            $ctypeid = (int)$_REQUEST['ctype'];
            $ctype = $this->get_content_type($ctypeid);
            if ($ctype){

                if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
                    $alias = $this->aliasgen($_REQUEST['name']);
                } else {
                    $alias = $_REQUEST['alias'];
                }
                /* Проверим алиас на уникальность */
                $this->query = 'SELECT `alias` FROM `'.$ctype['alias'].'` WHERE `alias` LIKE ? ORDER BY `alias` ASC';
                $searchalias = '%'.$alias.'%';
                $aliases = $this->get_data(array($searchalias));
                if ($aliases){
                    /* Если такие уже есть */
                    if (count($aliases) > 1){
                        /* Вытащим последнего */
                        $last = array_pop($aliases);
                        $preg = '/([a-z0-9\_]+)_([0-9]+)$/';
                        /* И проверим есть ли у него на конце циферка...*/
                        if (preg_match($preg, $last['alias'], $regresults)){
                            /* Если есть увеличим ее на единичку...*/
                            $num = (int)$regresults[2]+ 1;
                            $alias = $regresults[1] . '_'.$num;
                        }

                    }
                    else {
                        /* А если циферок нет то добавим*/
                        $alias = $alias. '_1';
                    }
                }
                /* Обязательные поля */
                $data = array(
                    'name' => $_REQUEST['name'],
                    'alias' => $alias,
                    'category' => $_REQUEST['category'],
                    'status' => (bool)$_REQUEST['status'],
                );
                /* Если для данного типа контента включена поддержка SEO */
                if ($ctype['seo_enabled']){
                    $data['seotitle'] = $_REQUEST['seotitle'];
                    $data['seokeys'] = $_REQUEST['seokeys'];
                    $data['seodescription'] = $_REQUEST['seodescription'];
                }
                /* Дополнительные поля из Unicon */
                $fields = self::get_fields($ctype['id']);
                if ($fields){
                    $data = $this->pack_material_data($fields, $ctype, $alias);
                }
                /* Сгенерим запрос */
                $query = self::insert_query_generator($data, $ctype['alias']);
                if ($query){
                    $this->query = $query;

                    /*var_dump($fields);
                    var_dump($data);
                    var_dump($query);
                    var_dump($_REQUEST);
                    var_dump($_FILES);*/

                    $result = $this->set_data($data);
                    if ($result){
                        return array('error'=> false, 'message' => 'Материал успешно создан ID:'.$result);
                    }

                }
                else {
                    return array('error'=> true, 'message' => 'Ошибка генерации запроса к БД');
                }

            }
            else {
                return array('error'=> true, 'message' => 'Такой тип контента не существует');
            }

        }

    }


    function delete_material() {
        if ((empty($_REQUEST['ctype']) || ($_REQUEST['ctype'] == ''))
            &&
            (empty($_REQUEST['id']) || ($_REQUEST['id'] == ''))
        )
        {
            return array('error'=> true, 'message' => 'Ошибка в имени материала или ID Типа');
        }
        else {
            $id = (int)$_REQUEST['id'];
            $ctypeid = (int)$_REQUEST['ctype'];
            $ctype = $this->get_content_type($ctypeid);
            if ($ctype){
                $this->query = 'DELETE FROM `'.$ctype['alias'].'` WHERE `id` = ? ';
                $result = self::set_data(array($id));
                return $result;
            }
            else {
                return array('error'=> true, 'message' => 'Такой тип контента не существует');
            }





        }
    }


    public function update_material(){
        if ((empty($_REQUEST['name']) || ($_REQUEST['name'] == ''))
            &&
            (empty($_REQUEST['ctype']) || ($_REQUEST['ctype'] == ''))
            &&
            (empty($_REQUEST['id']) || ($_REQUEST['id'] == ''))
        )
        {
            return array('error'=> true, 'message' => 'Ошибка в имени материала или ID Типа');
        }
        else {
            $id = (int)$_REQUEST['id'];
            $ctypeid = (int)$_REQUEST['ctype'];
            $ctype = $this->get_content_type($ctypeid);
            if ($ctype){
                if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')) {
                    $alias = $this->aliasgen($_REQUEST['name']);
                } else {
                    $alias = $_REQUEST['alias'];
                }
                /* Проверим алиас на уникальность */
                $this->query = 'SELECT `alias` FROM `'.$ctype['alias'].'` WHERE `alias` LIKE :alias AND `id` != :id ORDER BY `alias` ASC';
                $searchalias = '%'.$alias.'%';
                $aliases = $this->get_data(array('alias' => $searchalias, 'id' => $id));
                if ($aliases){
                    /* Если такие уже есть */
                    if (count($aliases) > 1){
                        /* Вытащим последнего */
                        $last = array_pop($aliases);
                        $preg = '/([a-z0-9\_]+)_([0-9]+)$/';
                        /* И проверим есть ли у него на конце циферка...*/
                        if (preg_match($preg, $last['alias'], $regresults)){
                            /* Если есть увеличим ее на единичку...*/
                            $num = (int)$regresults[2]+ 1;
                            $alias = $regresults[1] . '_'.$num;
                        }

                    }
                    else {
                        /* А если циферок нет то добавим*/
                        $alias = $alias. '_1';
                    }
                }

                /* Дополнительные поля из Unicon */
                $fields = self::get_fields($ctype['id']);

                if ($fields){
                    $data = $this->pack_material_data($fields, $ctype, $alias);
                }
                else {
                    return array('error'=> true, 'message' => 'Ошибка запроса полей');
                }

                /* Сгенерим запрос */
                $query = self::update_query_generator($data, array('id' => $id) ,$ctype['alias']);
                if ($query){
                    /* Втыкаем id в массив с данными тут а не выше чтоб он не попал в запрос.*/
                    $data['id'] = $id;
                    $this->query = $query;

                    /*var_dump($fields);
                    var_dump($data);
                    var_dump($query);
                    var_dump($_REQUEST);
                    var_dump($_FILES);*/

                    $result = $this->set_data($data);
                    if ($result){
                        return array('error'=> false, 'message' => 'Материал успешно создан ID:'.$result);
                    }

                }
                else {
                    return array('error'=> true, 'message' => 'Ошибка генерации запроса к БД');
                }

            }
            else {
                return array('error'=> true, 'message' => 'Такой тип контента не существует');
            }

        }

    }

    public function get_all_in_unicon($ctype, $cat = null){
        if ($cat){
            $this->query = 'SELECT `id`, `name`, `order` FROM `'.$ctype['alias'].'` WHERE `category` = ?';
            $result = $this->get_data(array($cat));
        }
        else {
            $this->query = 'SELECT `id`, `name`, `order` FROM `'.$ctype['alias'].'`';
            $result = $this->get_data();
        }
        if ($result){
            return $result;
        }
        else {
            return false;
        }

    }
    public function get_materials_by_type($type, $id = null){
        if (!is_array($type)){
            return false;
        }
        if (is_int($id)){
            $prequery  = ' WHERE `id` = ?';
        }
        $this->query = 'SELECT * FROM `'.$type['alias'].'`'.$prequery;
        $result = $this->get_data(array($id));
        if ($result){
            if (count($result) == 1){
                return $result[0];
            }
            else {
                return $result;
            }
        }
        else {
            return false;
        }

    }
    public function prepare_material($fields, $material, $ctype_info){
        foreach ($fields as &$field){
            $tmp = '';
            switch ($field['alias']) {
                case 'image' : {
                    $imgs = $material[$field['field_alias']];
                    if (is_string($imgs)){
                        $field['image_settings'] = explode(';', $field['image_settings']);
                        $field['folder'] = $ctype_info['alias'];
                        $field['values'] = unserialize($imgs);
                    }
                    break;
                }
                case 'assign' : {
                    if ($field['multiple']){
                        $tmp = unserialize($material[$field['field_alias']]);
                        if (is_array($tmp)){
                            foreach ($tmp as $t){
                                //var_dump($t);
                                foreach ($field['values'] as &$val)
                                {
                                    if ($val['id'] == $t){
                                        $val['selected'] = true;
                                        //var_dump($val['id']);
                                    }

                                }
                            }
                        }
                        //var_dump($field);
                    }
                    else {
                        $field['value'] = $material[$field['field_alias']];
                    }

                    break;
                }
                case 'select' : {
                    $tmp = unserialize($material[$field['field_alias']]);
                    if ($field['multiple']){
                        if (is_array($tmp)){
                            foreach ($field['values'] as $key => &$val){
                                $oldval = $val;
                                foreach ($tmp as $t){
                                    if ($val['string'] == $t){
                                        $val['selected'] = true;
                                    }
                                }
                            }
                            //$field['values'] = $values;
                        }
                        //var_dump($field);
                    }
                    else {
                        $field['value'] = $tmp[0];
                    }

                    break;
                }
                default : {
                $field['value'] = $material[$field['field_alias']];
                break;
                }

            }

        }
        return $fields;
    }
    public function pack_material_data($fields, $ctype, $alias){

        /* Обязательные поля */
        $data = array(
            'name' => $_REQUEST['name'],
            'alias' => $alias,
            'category' => $_REQUEST['category'],
            'status' => (bool)$_REQUEST['status'],
        );
        /* Если для данного типа контента включена поддержка SEO */
        if ($ctype['seo_enabled']){
            $data['seotitle'] = $_REQUEST['seotitle'];
            $data['seokeys'] = $_REQUEST['seokeys'];
            $data['seodescription'] = $_REQUEST['seodescription'];
        }

            foreach ($fields as $field){
                if ($field['field_type'] == 5){
                    /* Если среди полей есть поля с изображениями */

                    /* Определим настройки изображения */
                    $width = 640;
                    $height = 480;
                    $tmwidth = 200;
                    $tmheight = 300;
                    $crop = true;
                    $image_settings = explode(';', $field['image_settings']);
                    if (is_array($image_settings)){
                        $width = $image_settings[0];
                        $height = $image_settings[1];
                        $crop = (bool)$image_settings [2];
                        if (isset($image_settings[3]) && isset($image_settings[4])){
                            $tmwidth = $image_settings[3];
                            $tmheight = $image_settings[4];
                        }
                    }

                    $uploaddir = images_folder . $ctype['alias'] . DS;

                    if (!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0755);
                    }
                    $aux_images = array();
                    $images = array();
                    $delalis = $field['field_alias'].'-delete';
                    if(isset($_REQUEST[$delalis])){
                        $data[$field['field_alias']] = '';
                    }
                    elseif(isset($_FILES[$field['field_alias']]['name'])) {
                        $images = self::rearrange($_FILES[$field['field_alias']]);
                        foreach ($images as $image) {
                            if (!empty($image['name'])) {
                                $tmpimg = self::checkimage($image, $uploaddir, $_REQUEST['name']);
                                if ($tmpimg != false) {
                                    $image = self::imageresize($tmpimg, $uploaddir, $width, $height, $crop);
                                    $aux_images_thumb = self::imagethumb($tmpimg, $uploaddir, $tmwidth, $tmheight);
                                    $aux_images[] = array ('image' => $image, 'thumb' => $aux_images_thumb);
                                }
                                else {
                                    echo('Ошибка загрузки файла - '.$php_errormsg);
                                }
                            }
                        }
                        if (!empty($aux_images)){
                            $data[$field['field_alias']] = serialize($aux_images);
                        }
                    };

                }
                elseif ($field['field_type'] == 7 || $field['field_type'] == 6){
                    if ($field['multiple']){
                        $data[$field['field_alias']] = serialize($_REQUEST[$field['field_alias']]);
                    }
                    else {
                        $data[$field['field_alias']] = $_REQUEST[$field['field_alias']][0];
                    }

                }

                else {
                    $data[$field['field_alias']] = $_REQUEST[$field['field_alias']];
                }


        }
        return $data;
    }

}
	