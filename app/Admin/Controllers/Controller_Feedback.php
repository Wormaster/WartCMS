<?php
namespace Admin\Controllers;
    class Controller_Feedback extends \Core\Controller
    {
        function __construct()
        {
            parent::__construct();
            $this->model = new \Admin\Models\Model_Feedback();
            $this->view->controller = ('admin');

            session_start();
        }

        function action_index()
        {
            if ($this->model->user() == true) {
                $data = array(
                    'messages' => $this->model->list_feedbacks('message'),
                    'consults' => $this->model->list_feedbacks('consult'),
                    'orders' => $this->model->list_feedbacks('order'),

                );
                $this->view->admingenerate('feedback.html.twig', $data);
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');

        }
        function action_clearmess()
        {
            if ($this->model->user() == true) {
                $this->model->clearmessages();
                header('Location: /'.$GLOBALS['lang'].'/admin/feedback');
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');

        }
        function action_clearcalls()
        {
            if ($this->model->user() == true) {
                $this->model->clearcalls();
                header('Location: /'.$GLOBALS['lang'].'/admin/feedback');
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');

        }
        function action_clearfiles()
        {
            if ($this->model->user() == true) {
                $this->model->clearfiles();
                header('Location: /'.$GLOBALS['lang'].'/admin/feedback');
            }
            else header('Location: /'.$GLOBALS['lang'].'/admin/');

        }




    }