<?php
namespace Admin\Models;
class Model_Unicon extends \Core\Model {

    function __construct() {
        parent::__construct();

    }



    /* Universal Content System - Unicon */

    function get_fieldtypes(){
        $this->query = 'SELECT * FROM `field_types`';
        $result = self::get_data();
        if($result){
            return $result;
        }
        else {
            return false;
        }
    }
    function recreate_content_type($id) {
        /* Получаем поля и типы */
        $this->query = 'SELECT `t1`.*, `t2`.`type` AS `type`, `t2`.`alias` AS `alias`
        FROM `content_fields` as `t1`
        LEFT JOIN `field_types` AS `t2`
        ON `t1`.`field_type` = `t2`.`id`
        WHERE `t1`.`content_id` = ?';
        $fields = self::get_data(array($id));

        /* Составляем часть запроса с кастомными полями */
        /*
         * @todo Обработка ошибок, а что например если такого типа по id не существует... а так же автоподстройку длинны полей
         *
         */
        $extrafields ='';
        if ($fields){

            foreach($fields as $field){
                /* Немного костылей но пока так... */
                if (($field['alias'] == 'image') || ($field['alias'] == 'longtext') || ($field['alias'] == 'assign')
                ){
                    $field['length'] = 65535;
                }

                $extrafields .= '`'.$field['field_alias'].'` '.$field['type'].'('.$field['length'].') NOT NULL, ';
            }
        }

        $this->query = 'SELECT * FROM `content_types` WHERE `id` = ?';
        $content_name = self::get_data(array($id));

        if($content_name){
            $table_name = $content_name[0]['alias'];
            if ($content_name[0]['seo_enabled']){
                $extrafields .= '
                `seotitle` varchar(255) NOT NULL,
                `seokeys` varchar(255) NOT NULL,
                `seodescription` varchar(255) NOT NULL,';
            }
        }
        else {
            return false;
        }
        /* Убиваем таблицу, и создаем ее заново */
        $this->query = '
        DROP TABLE IF EXISTS `'.$table_name.'`;
        CREATE TABLE IF NOT EXISTS `'.$table_name.'` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(500) NOT NULL,
        `alias` varchar(255) NOT NULL,
        `category` int(5) NOT NULL,
        '.$extrafields.'
        `status` int(1) NOT NULL,
        `order` int(3) NOT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
        ';
        $result = self::get_data();
        /*var_dump($result);
        var_dump($content_name);
        var_dump($fields);
        var_dump($extrafields);*/
        return $result;
    }

    public function delete_field() {
        if ($_REQUEST['id']){
            $id = (int)$_REQUEST['id'];
        }
        else {
            return false;
        }

        $oldfield = $this->get_field_info($id);
        $ctype = $this->get_content_type($oldfield['content_id']);

        $this->query = 'DELETE FROM `content_fields` WHERE `id` = ? ';
        $result = self::set_data(array($id));
        if($result){
            $this->query = 'ALTER TABLE `'.$ctype['alias'].'`
	                DROP COLUMN `'.$oldfield['field_alias'].'`';
            var_dump($this->query);
            $del = $this->get_data();
        }
        return $result;
    }

    public function update_field() {
        if ($_REQUEST['id']){
            $id = array('id' => (int)$_REQUEST['id']);
        }
        else {
            return false;
        }
        $vars = $_REQUEST;
        foreach ($vars as &$var) {
            if (!$vars['required']){
                $vars['required'] = 0;
            }
            $var = filter_var($var, FILTER_SANITIZE_STRING,array(
                'flags'=> FILTER_FLAG_ENCODE_AMP | FILTER_FLAG_STRIP_LOW
            ));

        }
        if ($this->reg['debug']){
            $this->firephp->log($vars, 'Содержимое запроса');
        }
        /*
         * @todo Добавить полноценную фильтрацию говна.
         * */
        $oldfield = $this->get_field_info($id['id']);


        $data = array(
            'content_id' => $vars['content_id'],
            'field_name' => $vars['name'],
            'field_alias' => $vars['alias'],
            'field_type' => $vars['field_type'],
            'length' => $vars['length'],
            'order' => $vars['order'],
            'assign_id' => $vars['assign_id'],
            'custom' => $vars['custom'],
            'image_settings' => $vars['image_settings'],
            'required' => $vars['required']
        );
        $queryprep = $this->update_query_generator($data, $id, 'content_fields');
        if ($queryprep){
            $data = array_merge($data, $id);
            $this->query = $queryprep;
            $result = $this->set_data($data);

            if ($this->reg['debug']){
                $this->firephp->log($queryprep, 'SQL Запрос');
            }
            if ($result){
                $fieldtype = $this->get_field_type($data['field_type']);
                $ctype = $this->get_content_type($data['content_id']);

                if ($fieldtype && $ctype){
                    if (($fieldtype['alias'] == 'image') || ($fieldtype['alias'] == 'longtext') || ($fieldtype['alias'] == 'assign')
                    ){
                        $fieldtype['length'] = 65535;
                    }
                    else {
                        $fieldtype['length'] = $data['length'];
                    }

                    $this->query = 'ALTER TABLE `'.$ctype['alias'].'`
	                CHANGE COLUMN `'.$oldfield['field_alias'].'` `'.$data['field_alias'].'` '.$fieldtype['type'].'('.$fieldtype['length'].') NOT NULL AFTER `category`';
                    //var_dump($this->query);
                    $alter = $this->get_data();
                    //var_dump($alter);
                }

            }
            return $result;
        }
        else {
            return false;
        }
        /* @todo Допичсать возможность обновления таблицы на лету */
    }



    function new_field() {
        $vars = $_REQUEST;
        foreach ($vars as &$var) {
            if (!$vars['required']){
                $vars['required'] = 0;
            }
            $var = filter_var($var, FILTER_SANITIZE_STRING,array(
                'flags'=> FILTER_FLAG_ENCODE_AMP | FILTER_FLAG_STRIP_LOW
            ));

        }
        if ($this->reg['debug']){
            $this->firephp->log($vars, 'Содержимое запроса');
        }
        /*
         * @todo Добавить полноценную фильтрацию говна.
         * */

        $data = array(
            'content_id' => $vars['content_id'],
            'field_name' => $vars['name'],
            'field_alias' => $vars['alias'],
            'field_type' => $vars['field_type'],
            'length' => $vars['length'],
            'order' => $vars['order'],
            'assign_id' => $vars['assign_id'],
            'custom' => $vars['custom'],
            'image_settings' => $vars['image_settings'],
            'required' => $vars['required']
        );
        $queryprep = $this->insert_query_generator($data, 'content_fields');
        if ($queryprep){
            $this->query = $queryprep;
            $result = $this->set_data($data);

            if ($this->reg['debug']){
                $this->firephp->log($queryprep, 'SQL Запрос');
            }
            if ($result){
                $fieldtype = $this->get_field_type($data['field_type']);
                $ctype = $this->get_content_type($data['content_id']);
                if ($fieldtype && $ctype){
                    if (($fieldtype['alias'] == 'image') || ($fieldtype['alias'] == 'longtext') || ($fieldtype['alias'] == 'assign')
                    ){
                        $fieldtype['length'] = 65535;
                    }
                    else {
                        $fieldtype['length'] = $data['length'];
                    }

                    $this->query = 'ALTER TABLE `'.$ctype['alias'].'`
	                ADD COLUMN `'.$data['field_alias'].'` '.$fieldtype['type'].'('.$fieldtype['length'].') NOT NULL AFTER `category`';
                    //var_dump($this->query);
                    $alter = $this->get_data();
                    //var_dump($alter);
                }

            }
            return $result;
        }
        else {
            return false;
        }
        /* @todo Допичсать возможность обновления таблицы на лету */
    }


    /* Перенести в свою модель */

    public function get_field_info($id){
        $this->query = 'SELECT * FROM `content_fields` WHERE `id` =?';
        $result = $this->get_data(array($id));
        if ($result){
            return $result[0];
        }
        else return false;
    }
    private function get_field_type($id){
        $this->query = 'SELECT * FROM `field_types` WHERE `id` = ?';
        $result = $this->get_data(array($id));
        if ($result){
            return $result[0];
        }
        else return false;
    }

}
	