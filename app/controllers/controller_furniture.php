<?php

class Controller_Furniture extends Controller
{
		function __construct()
	{
		$this->model = new Model_Furniture();
		$this->view = new View();
        $this->view->controller = ('furniture');
	}
	function action_index()
	{
		$data = $this->model->get_all();
		$this->view->generate('furniture_view.php', 'template_view.php', $data);
		}
	function action_show()
	{
        /* Сомнительынй кусок */
        $this->view->mainmenu = $this->model->getmenu(mainmenu);
        if (!empty($_REQUEST['id'])) {
            $this->view->currentitem = (int)($_REQUEST['id']);
        }
        /* Конец сомнительного куска... работать то работает но нихера ни изящно ))) */

        $menu = $this->model->getmenuitem($_REQUEST['id']);
		$item = $this->model->get_item();
		$data['item'] = $item[0];
        if (empty($data['item'])) {
            Router::ErrorPage404();
            die;
        }
		$data['menu'] = $this->model->get_all();
		$data['images'] = unserialize($data['item']['images']);
		$headdata = array('title' => $data['item']['seotitle'], 'keywords' => $data['item']['seokeys'],'description' => $data['item']['seodescription']);
        //var_dump($data['item'], $menu);
        if ($menu['0']['template'] == 'single') {
            $this->view->generate('furniture_singleitem_view.php', 'template_view.php', $data, $headdata);
        }
        else {
            $this->view->generate('furniture_item_view.php', 'template_view.php', $data, $headdata);
        }
	}
}