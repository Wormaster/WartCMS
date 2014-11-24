<?php
namespace Admin\Controllers;
class Controller_Unicon extends \Core\Controller
{
    private $accesslevel;
    function __construct()
    {
        parent::__construct();
        $this->model = new \Admin\Models\Model_Unicon();
        $this->view->controller = ('unicon');
        $this->accesslevel = 'admin';

        session_start();
    }

    /* Сортировка */
    function action_saveorder() {
        if ($this->model->user($this->accesslevel) == true) {
            $result = $this->model->saveorder();
            var_dump($result);
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

    /* Список типов */
    function action_index($status = null) {
        if ($this->model->user($this->accesslevel) == true) {
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
            $data = array(
                'current_type' => $content_type,
                'types' => $this->model->get_content_type(),
                'fields' => $this->model->get_fields($content_type)
            );

            if (isset($status)) {
                $data['status'] = $status;
            }


            $this->view->admingenerate('unicon.html.twig', $data);
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    /* Пересоздание таблиц для контента */
    function action_recreate(){
        if ($this->model->user($this->accesslevel) == true) {
            if (empty($_REQUEST['id'])) {
                self::action_index('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }
            else {
                $result = $this->model->recreate_content_type($_REQUEST['id']);
            }
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

    function action_newfield($status = null) {
        if ($this->model->user($this->accesslevel) == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                if (
                    (empty($_REQUEST['name']) || ($_REQUEST['name'] == ''))
                    &&
                    (empty($_REQUEST['alias']) || ($_REQUEST['alias'] == ''))
                    &&
                    (empty($_REQUEST['field_type']) || ($_REQUEST['field_type'] == ''))
                    &&
                    (empty($_REQUEST['content_id']) || ($_REQUEST['content_id'] == ''))
                ) {
                    self::action_index('Произошла ошибка, скорее всего заполнены не все обязательные поля');
                    die;
                }
                $result = $this->model->new_field();
                $status = is_numeric($result)? 'Операция выполненна успешно, материал создан ID:'.$result : $result;
                $_SESSION['done'] = 1;
                self::action_index($status);
                var_dump($status);
            }
            else {
                $data = array(
                    'fields' => $this->model->get_fieldtypes(),
                    'content_types' => $this->model->get_content_type(),
                    'current' => $_SESSION['ctype'],
                    'status' => $status
                );
                $this->view->admingenerate('unicon-new.html.twig', $data);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else {
            header('Location: /'.$GLOBALS['lang'].'/admin/');
        }
    }
    function action_edit($status = null)
    {
        if ($this->model->user($this->accesslevel) == true) {
            if (!empty($_REQUEST['id'])) {
                $item = $_REQUEST['id'];
            }
            else {
                echo 'Гдето закралась проблемка...';
                die;
            }
            $item = $this->model->get_field_info($item);

            $data = array (
                'item'=>$item,
                'fields' => $this->model->get_fieldtypes(),
                'content_types' => $this->model->get_content_type(),
                'current' => $_SESSION['ctype'],
                'status' => $status
            );

            $this->view->admingenerate('unicon-new.html.twig', $data);
            $_SESSION['done'] = 0;
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_save(){
        if ($this->model->user($this->accesslevel) == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                $result = $this->model->update_field();
                $_SESSION['done'] = 1;
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                self::action_index($status);
            }
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');

    }
    function action_delete(){
        if ($this->model->user($this->accesslevel) == true) {
            if (empty($_REQUEST['id'])) {
                self::action_index('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }

            $result = $this->model->delete_field();
            $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
            $_SESSION['done'] = 1;
            self::action_index($status);

        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }

}