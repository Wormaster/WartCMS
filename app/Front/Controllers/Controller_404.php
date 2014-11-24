<?php
namespace Front\Controllers;

class Controller_404 extends \Core\Controller
{
		function __construct()
	{
		$this->view = new \Core\View();
        $this->reg = \Core\Registry::instance();
	}
	
	function action_index()
	{
        $this->model = new \Core\Model();
        $data = array (
            'title' => '404',
            'error' => 'К великому сожалению,<br>запрашиваемая страница не найдена.',
            'menu' => $this->model->getmenu('mainmenu'));
		$this->view->generate('404.html.twig', $data);
	}
    function action_nodb($error = null)
    {
        if (!empty($error)) {

            $err = preg_match('/[\[](\d+)[\]]+/', $error, $numbers);
            if ($numbers[1] == 28000){
                $data['error'] = 'Ошибка! Проверьте конфигурацию БД - '.$numbers[1];
            }
            else {
                $data['error'] = 'Ошибка соединения с БД';
            }

        }
        else {
            $data['error'] = 'Ошибка соединения с БД';
        }
        echo $data['error'];
        //$this->view->generate_old('404.php', 'template_view.php', $data);
    }
    function action_offline()
    {
        $this->view->generate('dummy.html.twig', null);
    }
}