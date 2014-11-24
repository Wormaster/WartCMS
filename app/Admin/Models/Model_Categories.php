<?php
namespace Admin\Models;
class Model_Categories extends \Core\Model {

    public $fields;

    function __construct() {
        parent::__construct();
        $this->fields = array(
            array('alias' => 'text', 'field_name' => 'Название', 'field_alias' => 'name'),
            array('alias' => 'text', 'field_name' => 'Алиас', 'field_alias' => 'alias'),
            array('alias' => 'catassign', 'field_name' => 'Тип контента', 'field_alias' => 'type', 'values' => $this->get_content_type()),
            array('alias' => 'longtext', 'field_name' => 'Описание', 'field_alias' => 'description'),
            array('alias' => 'image', 'field_name' => 'Изображение категории', 'field_alias' => 'image', 'image_settings' => '287;219;false'),
            array('alias' => 'text', 'field_name' => 'Заголовок браузера', 'field_alias' => 'seotitle'),
            array('alias' => 'text', 'field_name' => 'Ключевые слова', 'field_alias' => 'seokeys'),
            array('alias' => 'text', 'field_name' => 'Description', 'field_alias' => 'seodescription'),
        );
    }



    /* Categories */

    function delete_category() {
        if ($_REQUEST['id']){
            $id = (int)$_REQUEST['id'];
        }
        else {
            return false;
        }
        $this->query = 'DELETE FROM `categories` WHERE `categories`.`id` = ? ';
        $result = self::set_data(array($id));
        return $result;
    }
    function update_category() {
        if (
            (!empty($_REQUEST['id'])) || ($_REQUEST['id'] == '')
            &&
            (!empty($_REQUEST['name'])) || ($_REQUEST['name'] == '')
            &&
            (!empty($_REQUEST['type'])) || ($_REQUEST['type'] == '')
        )
        {
            $data = $this->pack_data($_REQUEST);
            if ($data){
                $where = array('id' => (int)$_REQUEST['id']);
                $query = $this->update_query_generator($data, $where, 'categories');
                $data = array_merge($data, $where);
                if ($query){
                    $this->query = $query;
                    $result = $this->set_data($data);
                    return $result;
                }
                else {
                    return array('error' => true, 'message' => 'Ошибка генерации запроса');
                }
            }
            else {
                return array('error' => true, 'message' => 'Ошибка упаковки данных');
            }
        }
        else {
            return array('error' => true, 'message' => 'Отсутствует имя, тип или ID');
        }
    }

    function new_category() {
        if (
        (!empty($_REQUEST['name'])) || ($_REQUEST['name'] == '')
            &&
        (!empty($_REQUEST['type'])) || ($_REQUEST['type'] == '')
        )
        {
            if (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == '')){
                $_REQUEST['alias'] = self::aliasgen($_REQUEST['name']);
            }

            $data = $this->pack_data($_REQUEST);
            if ($data){
                $query = $this->insert_query_generator($data, 'categories');
                if ($query){
                    $this->query = $query;
                    $result = $this->set_data($data);
                    return $result;
                }
                else {
                    return array('error' => true, 'message' => 'Ошибка генерации запроса');
                }
            }
            else {
                return array('error' => true, 'message' => 'Ошибка упаковки данных');
            }

        }
        else {
            return array('error' => true, 'message' => 'Отсутствует имя или тип');
        }
    }

    private function pack_data($request){
        $data = array();
        foreach ($this->fields as $field){
            if ($field['alias'] == 'image'){
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
                    if ($image_settings[3] && $image_settings[4]){
                        $tmwidth = $image_settings[3];
                        $tmheight = $image_settings[4];
                    }
                }

                $uploaddir = images_folder . 'categories' . DS;

                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0755);
                }
                if  (isset($_FILES[$field['field_alias']]['name'])) {
                    $images = self::rearrange($_FILES[$field['field_alias']]);
                    foreach ($images as $image) {
                        if (!empty($image['name'])) {
                            $tmpimg = self::checkimage($image, $uploaddir, $request['name']);
                            if ($tmpimg != false) {
                                $image = self::imageresize($tmpimg, $uploaddir, $width, $height, $crop);
                                /*@todo Вынести в настройки генерацию превьюхи и ее размер*/
                                $aux_images_thumb = self::imagethumb($tmpimg, $uploaddir, $tmwidth, 244);
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
            elseif ($field['alias'] == 'catassign'){
                $id = $request[$field['field_alias']][0];
                $ctype = $this->get_content_type($id);
                if ($ctype){
                    $data[$field['field_alias']] = $ctype['alias'];
                }
                else {
                    return array('error' => true, 'message' => 'Ошибка. Такой тип контента не существует');
                }
            }
            else {
                $data[$field['field_alias']] = $request[$field['field_alias']];
            }


        }
        //endforeach
        return $data;
    }
    public function data_to_fields($data){
        $newdata = array();
        foreach ($this->fields as $field){
            $new = array();
            switch ($field['alias']) {
                case 'image' : {

                    $imgs = $data[$field['field_alias']];
                    $new['image_settings'] = explode(';', $field['image_settings']);
                    $new['folder'] = 'categories';
                    $new['values'] = $imgs;
                    $new = array_merge($new, $field);

                    break;
                }
                case 'catassign' : {
                    $new['value'] = $data[$field['field_alias']];
                    $new = array_merge($new, $field);
                    break;
                }
                default : {
                $new['value'] = $data[$field['field_alias']];
                $new = array_merge($new, $field);
                break;
                }

            }
            $newdata[] = $new;

        }
        return $newdata;

    }

}
	