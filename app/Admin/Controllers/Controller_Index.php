<?php
namespace Admin\Controllers;

    class Controller_Index extends \Core\Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->view->controller = ('admin_index');

            session_start();
        }

        function action_index()
        {
            $stat = new \Generic\Statistics();
            $unicon = new \Admin\Models\Model_Unicon();
            $user = new \Admin\Models\Model_Users();
            if ($this->model->user() == true) {
                $data = array(
                    'fields' => $unicon->get_fieldtypes(),
                    'freespace' => round(disk_free_space("/")/pow(1024, 3), 0),
                    'today' => $stat->get_stat_by_day(),
                    'users' => $user->list_users()
                );
                $this->view->admingenerate('index.html.twig', $data);
            }
            else $this->action_login();

        }

        function action_login()
        {
            if ($this->model->user() == true) {
                $data = $_SESSION['admin'];
                $this->view->admingenerate('index.html.twig', $data);
            }
            else {
                if (isset($_REQUEST['confirm'])) {
                    if (!empty($_REQUEST["login"]) && !empty($_REQUEST["pass"])){
                        $auth = $this->model->authenticate('manager');
                        if (!$auth){
                            $data['message'] = 'К сожалению такой логин и/или пароль не найден в системе';
                        }
                        else {
                            header('Location: /'.$GLOBALS['lang'].'/admin/');
                        };
                    }
                    else {
                        $data['message'] = 'Логин и/или пароль не может быть пустым';

                    }
                }}
            $this->view->admingenerate('login.html.twig', $data);
        }
        function action_logout()
        {

            $_SESSION = array();
            session_destroy();
            header('Location: /'.$GLOBALS['lang'].'/admin/');
            exit;
        }


    }