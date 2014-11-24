<?php
/**
 * Created by PhpStorm.
 * User: Arkady
 * Date: 12.12.13
 * Time: 14:53
 */
namespace Admin\Controllers;
class Controller_User extends \Core\Controller {
    function __construct(){
        parent::__construct();
        $this->model = new \Admin\Models\Model_Users();
        $this->view->controller = ('user');

        session_start();
    }
    function action_index($status = null) {
        if ($this->model->user() == true) {
            if ($_SESSION['admin'] != 1){
                $users = $this->model->list_users('admin');
            }
            else {
                $users = $this->model->list_users();
            }
            $unicon = new \Admin\Models\Model_Unicon();
            $data = array (
                'items' => $users,
                'status' => $status,
            );

            $this->view->admingenerate('users.html.twig', $data);
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_edit($status = null)
    {
        if ($this->model->user() == true) {
            if (!empty($_REQUEST['id'])) {
                $itemid = $_REQUEST['id'];
            }
            else {
                self::action_index('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }
            $item = $this->model->get_user($itemid);
            if ($item){
                $fields = $this->model->data_to_fields($item);
            }
            $unicon = new \Admin\Models\Model_Unicon();
            $data = array (
                'item'=>$item,
                'fields' => $fields,
                'status' => $status,
        );

            $this->view->admingenerate('users-new.html.twig',  $data);
            $_SESSION['done'] = 0;
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_save(){
        if ($this->model->user() == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {

                $result = $this->model->update_user();
                $_SESSION['done'] = 1;
                $status = $result==true? 'Операция выполненна успешно': $result;
                self::action_index($status);
            }
            else { self::action_index('Операция уже выполненна'); }
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_delete() {

        if ($this->model->user() == true) {
            if (empty($_REQUEST['id'])) {
                self::action_edit('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                die;
            }

                $result = $this->model->delete_user();
                $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';

                self::action_index($status);

        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
    function action_new($status = null) {

        if ($this->model->user() == true) {
            if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                if (empty($_REQUEST['name']) || ($_REQUEST['name'] == '')) {
                    self::action_index('У пользователя должно быть имя');
                    die;
                }
                $result = $this->model->new_user();
                $status = $result==true? 'Операция выполненна успешно, материал создан': $status;
                $_SESSION['done'] = 1;
                self::action_index($status);
            }
            else {
                $data = array(
                    'fields' => $this->model->return_fields(),
                    'status' => $status
                );
                $this->view->admingenerate('users-new.html.twig', $data);
                $_SESSION['done'] = 0;
                die;
            }
        }
        else header('Location: /'.$GLOBALS['lang'].'/admin/');
    }
} 