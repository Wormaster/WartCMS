<?php

class Controller_Calculator extends Controller
{
		function __construct()
	{
		$this->model = new Model_Calculator();
		$this->view = new View();
	}
	function action_index()
	{
		for ($i=0; $i<7; $i++){
			$data[$i] = $this->model->get_items($i);
		}
		$this->view->generate('calculator_view.php', 'template_view.php', $data);
		}
	function action_write()
	{
		$cat = $this->model->get_data();
		if (isset($_REQUEST['razdel'])){
		$_SESSION['razdel'] = $_REQUEST['razdel'];
		}
		if (empty($_SESSION['razdel'])) {
			 $_SESSION['razdel'] = 0;
		}
		$this->model->prepare_query();
		$razdel = array ($_SESSION['razdel']);
		$items = $this->model->get_data($razdel);
		$data = array ('categories'=>$cat, 'items'=>$items, 'razdel'=> $_SESSION['razdel']);
		
		$this->view->generate('list_calc_view.php', 'template_view.php', $data);
	}
}