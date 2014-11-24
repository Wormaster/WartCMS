<?php
/**
 * Created by PhpStorm.
 * User: Wormaster
 * Date: 05.01.14
 * Time: 7:57
 */

namespace Front\Controllers;

class Controller_Feedback extends \Core\Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new \Front\Models\Model_Feedback;
    }

    function action_callme(){
        $result = $this->model->callme();
        if ($result){
            if ($_REQUEST['ajax']){
                echo json_encode(true);
            }
            else {
                header('Location: /');
            }
        }
        else {
            if ($_REQUEST['ajax']){
                echo 'false';
            }
            else {
                header('Location: /');
            }
        }

    }
    function action_message() {
        $result = $this->model->message();
        if ($result){
            echo 'true';
        }
        else {
            echo 'false';
        }
    }
    function action_file() {
        if ($this->reg['fileupload']){
            $result = $this->model->file();
            if ($result){
                echo 'success';
            }
            else {
                echo 'false';
            }
        }
        else {
            echo 'Данная операция не разрешена администратором сервера';
        }

    }
    function action_index(){
        if ($_REQUEST['ajax'] == true){
            $data = array(

            );
            $this->view->generate('feedback.html.twig', $data);
        }
        else {\Core\Router::ErrorPage404();}
    }
} 