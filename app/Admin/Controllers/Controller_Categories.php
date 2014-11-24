<?php
namespace Admin\Controllers;
    class Controller_Categories extends \Core\Controller
    {


        function __construct()
        {
            parent::__construct();
            $this->model = new \Admin\Models\Model_Categories();
            $this->view->controller = ('categories');
            session_start();
        }

        /* Сортировка */
        function action_saveorder() {
            if ($this->model->user() == true) {
                $result = $this->model->saveorder();
                var_dump($result);
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');
        }

        function action_index($status = null) {
            if ($this->model->user() == true) {
                $data = array(
                    'items' => $this->model->get_allcat(true),
                );
                $this->view->admingenerate('categories.html.twig', $data);

            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');
        }
        function action_new($status = null) {
            if ($this->model->user() == true) {
                if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {

                    $result = $this->model->new_category();
                    $status = is_numeric($result)? 'Операция выполненна успешно, материал создан ID:'.$result : $result;
                    $_SESSION['done'] = 1;
                    self::action_index($status);
                }
                else {
                    $data = array(
                        'fields' => $this->model->fields
                    );
                    $this->view->admingenerate('categories-new.html.twig', $data);
                    $_SESSION['done'] = 0;
                    die;
                }
            }
            else {
                header('Location: /'.$GLOBALS['lang'].'/admin/');
            }
        }
        function action_edit()
        {
            if ($this->model->user() == true) {
                if (!empty($_REQUEST['id'])) {
                    $id = $_REQUEST['id'];
                }
                else {
                    //@todo Привести в порядок сообщения об ошибках
                    self::action_index('Гдето закралась проблемка...');
                    die;
                }
                $item = $this->model->get_cat_by_id($id);
                if ($item){
                    $fields = $this->model->data_to_fields($item);
                }
                //Место для ошибки о несуществующей категории!
                $data = array(
                    'item' => $this->model->get_cat_by_id($id),
                    'fields' => $fields
                );
                $this->view->admingenerate('categories-new.html.twig', $data);
                $_SESSION['done'] = 0;
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');
        }
        function action_save(){
            if ($this->model->user() == true) {
                if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                    $result = $this->model->update_category();
                    $_SESSION['done'] = 1;
                    $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                    self::action_index($status);
                }
                else {
                    //@todo Привести в порядок сообщения об ошибках
                    self::action_index('Гдето закралась проблемка...');
                    die;
                }
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');

        }
        function action_delete(){
            if ($this->model->user() == true) {
                if (empty($_REQUEST['id'])) {
                    self::action_index('К сожалению произошла ошибка в передаче ID. Попробуйте еще раз.');
                    die;
                }
                else {
                    $result = $this->model->delete_category();
                    $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                    self::action_index($status);
                }
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');
        }

    }