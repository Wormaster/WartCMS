<?php
namespace Admin\Models;
class Model_Users extends \Core\Model {
    private $userfields;

    function __construct() {
        parent::__construct();
        $this->userfields = array(
            array('alias' => 'text', 'field_name' => 'Логин', 'field_alias' => 'name'),
            array('alias' => 'text', 'field_name' => 'Имя', 'field_alias' => 'hfname'),
            array('alias' => 'password', 'field_name' => 'Пароль', 'field_alias' => 'pass'),
            array('alias' => 'userlevel', 'field_name' => 'Уровень доступа', 'field_alias' => 'level', 'values' => $this->get_userlevels()),
            array('alias' => 'text', 'field_name' => 'Компания', 'field_alias' => 'company'),
            array('alias' => 'status', 'field_name' => 'Статус', 'field_alias' => 'status', 'values' => array('0', '1'), 'current' => 1)
        );
    }


    function list_users($restrict = null) {

        if ($restrict) {
            $this->query = 'SELECT * FROM `user` WHERE `level` NOT LIKE ?';
            $result = self::get_data(array($restrict));
        }
        else {
            $this->query = 'SELECT * FROM `user`';
            $result = self::get_data();
        }

        return $result;
    }
    function get_user($id) {
        $this->query = 'SELECT * FROM `user` WHERE `id` = ? ';
        $result = self::get_data(array($id));
        return $result['0'];
    }

    private function get_userlevels(){
        $this->query = 'SELECT * FROM `userlevels`';
        $result = $this->get_data();
        if ($result){
            return $result;
        }
        else return false;
    }

    function delete_user() {
        if ($_REQUEST['id']){
            $id = (int)$_REQUEST['id'];
        }
        else {
            return false;
        }
        $this->query = 'DELETE FROM `user` WHERE `id` = ?';
        $result = self::set_data(array($id));
        return $result;
    }

    function update_user() {
        if (
            (!empty($_REQUEST['id'])) && ($_REQUEST['id'] != '')
            &&
            (!empty($_REQUEST['name'])) && ($_REQUEST['name'] != '')
            &&
            (!empty($_REQUEST['level'])) && ($_REQUEST['level'] != '')
        )
        {
            $data = $this->pack_data($_REQUEST);
            if ($data){
                $where = array('id' => (int)$_REQUEST['id']);
                $query = $this->update_query_generator($data, $where, 'user');
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

    function new_user() {
        if (
            (!empty($_REQUEST['name'])) && ($_REQUEST['name'] != '')
            &&
            (!empty($_REQUEST['level'])) && ($_REQUEST['level'] != '')
            &&
            (!empty($_REQUEST['pass'])) && ($_REQUEST['pass'] != '')
        )
        {
            if (empty($_REQUEST['hfname']) || ($_REQUEST['hfname'] == '')){
                $_REQUEST['hfname'] = self::aliasgen($_REQUEST['name']);
            }

            $data = $this->pack_data($_REQUEST);
            if ($data){
                $query = $this->insert_query_generator($data, 'user');
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
        foreach ($this->userfields as $field){
                if ($field['alias'] == 'assign'){
                    $data[$field['field_alias']] = $request[$field['field_alias']][0];
                }
                elseif ($field['alias'] == 'password'){
                    if ($request[$field['field_alias']]){
                        $data[$field['field_alias']] = $this->passtohash($request[$field['field_alias']]);
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
        foreach ($this->userfields as $field){
            $new = array();
            switch ($field['alias']) {
                case 'assign' : {
                    var_dump($field['field_alias']);
                    $new['current'] = $data[$field['field_alias']];
                                    var_dump($new['current']);
                    $new = array_replace($new, $field);
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
    public function return_fields(){
        return $this->userfields;
    }


}
	