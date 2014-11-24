<?php
namespace Admin\Controllers;
class Controller_Unimanager extends \Core\Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new \Admin\Models\Model_Unimanager();
        $this->view->controller = ('unimanager');

        //session_start();
    }

    /* Сортировка */
    function action_saveorder() {
        if ($this->model->user() == true) {
            $result = $this->model->saveorder();
            var_dump($result);
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

    /* Список типов */
    function action_index($status = null) {
        if ($this->model->user() == true) {

            if (!empty($_REQUEST['ctype']))  {
                $content_type = $_SESSION['ctype'] = $_REQUEST['ctype'];
            }
            else {
                if (!empty($_SESSION['ctype'])) {
                    $content_type = $_SESSION['ctype'];
                }
                else {
                    $content_type = 2;
                }
            }

            $ctype_info = $this->model->get_content_type($content_type);
            if ($ctype_info){
                if (!empty($_REQUEST['category']) || $_REQUEST['category'] == 0)  {
                    $cat = $_SESSION['category'] = $_REQUEST['category'];
                }
                else {
                    if (!empty($_SESSION['category'])) {
                        $cat = $_SESSION['category'];
                    }
                    else {
                        $cat = 0;
                    }
                }



                if ($cat == 0){
                    $content = $this->model->get_all_in_unicon($ctype_info);
                }
                else {
                    $content = $this->model->get_all_in_unicon($ctype_info, $cat);
                }

            }
            else {
                //@todo Надо бы какой нибудь ошибкой плюнуть
            }
            $data = array(
                'ctype' => $ctype_info,
                'items' => $content,
                'categories' => $this->model->get_categories($ctype_info['alias']),
                'current' => $cat,
            );

            if (isset($status)) {
                $data['status'] = $status;
            }


            $this->view->admingenerate('unimanager.html.twig', $data);
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    /* Пересоздание таблиц для контента */

    function action_new($params = null) {

        if ($this->model->user() == true) {
            if ((isset($_REQUEST['confirm'])) && ($_SESSION['done'] == 0)) {


                $result = $this->model->new_material();

                $_SESSION['done'] = 1;
                self::action_index($result);
            }
            else {

                if (!empty($_REQUEST['ctype']))  {
                    $content_type = $_SESSION['ctype'] = $_REQUEST['ctype'];
                }
                else {
                    if (!empty($_SESSION['ctype'])) {
                        $content_type = $_SESSION['ctype'];
                    }
                    else {
                        $content_type = 2;
                    }
                }

                $ctype_info = $this->model->get_content_type($content_type);

                if ($ctype_info){
                    $fields = $this->model->get_fields($ctype_info['id'], true);


                    $data = array (
                        'ctype' => $ctype_info,
                        'fields' => $fields,
                        'categories' => $this->model->get_categories($ctype_info['alias']),
                        'current' => $_SESSION['category']
                    );

                }
                else {
                    self::action_index('Такого типа контента не существует');
                    die;
                }
                //var_dump($data);
                $this->view->admingenerate('unimanager-new.html.twig', $data);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

    function action_edit()
    {
        if ($this->model->user() == true) {
            if (!empty($_REQUEST['id']) && !empty($_REQUEST['ctype'])) {
                $item = (int)$_REQUEST['id'];
                $ctype = (int)$_REQUEST['ctype'];
            }
            else {
                self::action_index('Ошибка передачи ID материала или типа');
                die;
            }
            $ctype_info = $this->model->get_content_type($ctype);
            if ($ctype_info){
                $fields = $this->model->get_fields($ctype_info['id'], true);
                $material = $this->model->get_materials_by_type($ctype_info, $item);
                //@todo Перенести это безобразие в модель!!!!
                if ($material){
                    $this->firephp->log($material, 'Material');
                    if ($fields){
                        $fields = $this->model->prepare_material($fields, $material, $ctype_info);
                    }

                    $data = array (
                        'ctype' => $ctype_info,
                        'item' => $material,
                        'fields' => $fields,
                        'categories' => $this->model->get_categories($ctype_info['alias']),
                        'current' => $_SESSION['category']
                    );
                }
                else {
                    self::action_index('Такого материала не существует');
                    die;
                }
            }
            else {
                self::action_index('Такого типа контента не существует');
                die;
            }

            $this->view->admingenerate('unimanager-new.html.twig', $data);
            $_SESSION['done'] = 0;

        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_save(){
        if ($this->model->user() == true) {

            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                $result = $this->model->update_material();
                $_SESSION['done'] = 1;
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                self::action_index($status);
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/unimanager');

        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_delete(){
        if ($this->model->user() == true) {
                /* @todo Защиту от дурака надобы... */
                $result = $this->model->delete_material();
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                $_SESSION['done'] = 1;
                self::action_index($status);

        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

}