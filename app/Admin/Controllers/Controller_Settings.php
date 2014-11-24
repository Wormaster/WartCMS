<?php
namespace Admin\Controllers;
    class Controller_Settings extends \Core\Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->view->controller = ('settings');
            $this->model = new \Admin\Models\Model_Settings();

            session_start();
        }

        function action_index()
        {
            if ($this->model->user($status = null) == true) {
                $_SESSION['done'] = 0;
                $data = array(
                    'ctypes' => $this->model->get_ctypes(),
                    'fields' => $this->model->get_cfields(),
                    'item' => $this->model->get_settings('color'),
                    'status' => $status
                );

                $this->firephp->log($data, 'Data');
                $this->view->admingenerate('settings.html.twig', $data);

            }
            else $this->action_login();

        }
        function action_save($status){
            if ($this->model->user($status = null) == true) {
                if (($_REQUEST['confirm'] == true) && ($_SESSION['done'] == 0)) {
                    $result = $this->model->save_settings();
                    $_SESSION['done'] = 1;
                    $status = $result==true? 'Операция выполненна успешно': 'Произошла ошибка';
                    self::action_index($status);
                }
                else {
                    //$data['status'] = 'Произошла ошибка';
                    $_SESSION['done'] = 0;
                    $data = array(
                    'item' => $this->model->get_settings('color'),
                    'status' => $status
                );
                    $this->view->admingenerate('settings.html.twig', $data);
                }
            }
            else $this->action_login();




    }
}