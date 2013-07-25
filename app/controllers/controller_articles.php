<?php

class Controller_Articles extends Controller
{
		function __construct()
	{
		$this->model = new Model_Articles();
		$this->view = new View();
        $this->view->controller = ('articles');
	}
	function action_index()
	{
		/*for ($i=0; $i<7; $i++){
			$data[$i] = $this->model->get_items($i);
		}*/
		$i= 1;
		$article = $this->model->get_item($i, 1, 1);
		$data['article'] = $article[0];
		$data['items'] = $this->model->list_items();
        $headdata = array('title' => $data['article']['seotitle'], 'keywords' => $data['article']['seokeys'],'description' => $data['article']['seodescription']);
		$this->view->generate('articles_view.php', 'template_view.php', $data, $headdata);
		}
	function action_show()
	{
		/* Сомнительынй кусок */
        $this->view->mainmenu = $this->model->getmenu(mainmenu);
        if (!empty($_REQUEST['id'])) {
            $this->view->currentitem = (int)($_REQUEST['id']);
        }
        /* Конец сомнительного куска... работать то работает но нихера ни изящно ))) */
		if (!empty($_REQUEST['id'])) {
			$i = $_REQUEST['id'];
			}
		else {$i= 1;}
		$article = $this->model->get_item($i, 1, 1);
		$data['article'] = $article[0];
		$data['items'] = $this->model->list_items();
		if (empty($data['article'])) {
			Router::ErrorPage404();
			die;
			}
		$headdata = array('title' => $data['article']['seotitle'], 'keywords' => $data['article']['seokeys'],'description' => $data['article']['seodescription']);
		$this->view->generate('articles_view.php', 'template_view.php', $data, $headdata);
		}
}