<?php

class Controller_Main extends Controller
{
		function __construct()
	{
        $this->model = new Model_Main();
		$this->view = new View();
        $this->view->controller = ('main');
	}
	
	function action_index()
	{
		$this->view->generate('main_view.php', 'template_view.php', $data);
	}
	function action_psy()
	{	
		$this->view->generate('alterback.php', 'alter_template_view.php', $cat);
	}
}